<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Iyzipay\Options;
use App\Models\Order;
use GuzzleHttp\Client;
use App\Models\OrderItem;
use Iyzipay\Model\Payment;
use Illuminate\Http\Request;
use Iyzipay\Model\BasketItem;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Iyzipay\Request\CreatePaymentRequest;

class CheckoutController extends Controller
{



    function sendTelegramMessage($message) {
        $telegramBotToken = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        $client = new Client();
        
        try {
            $client->post("https://api.telegram.org/bot{$telegramBotToken}/sendMessage", [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML', // Optional: For formatting
                ],
            ]);
        } catch (\Exception $e) {
            // Handle exceptions if needed
            \Log::error("Telegram API error: " . $e->getMessage());
        }
    }
    

    public function index()
{
    // Kullanıcının sepetini al
    $userId = Auth::id();
    $cart = Cart::where('user_id', $userId)->with('cartItems.product')->first();

    // Sepet yoksa yönlendirme yap
    if (!$cart || $cart->cartItems->isEmpty()) {
        return redirect()->route('cart.view')->with('warning', 'Sepetinizde ürün yok. Lütfen ürün ekleyin.');
    }

    // Toplam fiyatı hesapla
    $totalPrice = $cart->cartItems->sum(function ($item) {
        return $item->product->price * $item->quantity;
    });

    // Kullanıcı bilgilerini al
    $user = Auth::user();

    // Checkout sayfasına veri geç
    return view('frontend.checkout', compact('cart', 'totalPrice', 'user'));
}


    public function process(Request $request)
    {
        // Validasyon
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|email|max:255|regex:/^.+@.+$/i',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:255',
                'zip_code' => 'required|string|max:20',
                'card_number' => 'required|string|max:16',
                'expiry_date' => 'required|string',
                'cvc' => 'required|string|max:4',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        

        // Kullanıcının sepetini al
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->with('cartItems.product')->first();

        // Sepetteki toplam fiyatı hesapla
        $totalPrice = $cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // İyzipay ödeme isteği oluştur
        $paymentRequest = new CreatePaymentRequest();
        $paymentRequest->setLocale('tr');
        $paymentRequest->setConversationId(uniqid());
        $paymentRequest->setPrice($totalPrice);
        $paymentRequest->setPaidPrice($totalPrice);
        $paymentRequest->setCurrency('TRY');
        $paymentRequest->setPaymentChannel('WEB');
        $paymentRequest->setPaymentGroup('PRODUCT');

        // Müşteri bilgileri
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(uniqid());
        $buyer->setName($request->input('name'));
        $buyer->setSurname($request->input('surname'));
        $buyer->setEmail($request->input('email'));
        $buyer->setIdentityNumber('11111111111'); // Kimlik numarası yerine daha güvenli bir uygulama kullanılabilir
        $buyer->setRegistrationAddress($request->input('address'));
        $buyer->setIp($request->ip());
        $buyer->setCity($request->input('city'));
        $buyer->setCountry('Turkey');
        $buyer->setZipCode($request->input('zip_code'));

        $paymentRequest->setBuyer($buyer);

        // Billing Address
        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($request->input('name') . ' ' . $request->input('surname'));
        $billingAddress->setCity($request->input('city'));
        $billingAddress->setCountry('Turkey');
        $billingAddress->setAddress($request->input('address'));
        $billingAddress->setZipCode($request->input('zip_code'));
        $paymentRequest->setBillingAddress($billingAddress);

        // Shipping Address
        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($request->input('name') . ' ' . $request->input('surname'));
        $shippingAddress->setCity($request->input('city'));
        $shippingAddress->setCountry('Turkey');
        $shippingAddress->setAddress($request->input('address'));
        $shippingAddress->setZipCode($request->input('zip_code'));
        $paymentRequest->setShippingAddress($shippingAddress);

        // Sepetteki her ürün için sepet oluştur
        $basketItems = [];
        foreach ($cart->cartItems as $cartItem) {
            $basketItem = new BasketItem();
            $basketItem->setId('BI_' . $cartItem->product->id);
            $basketItem->setName($cartItem->product->name);
            $basketItem->setCategory1($cartItem->product->category->name);
            $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $basketItem->setPrice($cartItem->product->price * $cartItem->quantity);
            $basketItems[] = $basketItem;
        }
        
        $paymentRequest->setBasketItems($basketItems);

        // Kart bilgileri
        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($request->input('name'));
        $paymentCard->setCardNumber($request->input('card_number'));
        $expiryDate = explode('/', $request->input('expiry_date'));
        $paymentCard->setExpireMonth($expiryDate[0]);
        $paymentCard->setExpireYear($expiryDate[1]);
        $paymentCard->setCvc($request->input('cvc'));
        $paymentCard->setRegisterCard(0);

        $paymentRequest->setPaymentCard($paymentCard);

        // İyzipay API'yi çağır ve ödemeyi işle
        $options = new Options();
        $options->setApiKey(env('IYZICO_API_KEY'));
        $options->setSecretKey(env('IYZICO_SECRET_KEY'));
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        // Ödeme işlemini gerçekleştirin
        $payment = Payment::create($paymentRequest, $options);

        if ($payment->getStatus() == 'success') {
            // Ödeme başarılı, sipariş oluştur
            
            // Order oluştur
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'status' => 'pending',  // Sipariş durumu
            ]);

            // OrderItems oluştur
            foreach ($cart->cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            // Sepet ve sepet öğelerini sil
            $cart->cartItems()->delete(); // Sepet öğelerini sil
            $cart->delete(); // Sepeti sil

            // Sipariş onay e-postasını gönder
            Mail::to($request->input('email'))->send(new OrderConfirmationMail($order));

            // Telegram'a bildirim gönder
            $telegramMessage = "Yeni sipariş alındı!\n";
            $telegramMessage .= "Sipariş ID: " . $order->id . "\n";
            $telegramMessage .= "Toplam Fiyat: " . $totalPrice . " ₺\n";
            $telegramMessage .= "Kullanıcı ID: " . $userId . "\n";
            $telegramMessage .= "Durum: " . $order->status . "\n";
            $this->sendTelegramMessage($telegramMessage); // Telegram bildirimi gönder

            return redirect()->route('checkout.success')->with('success', 'Sipariş bilgileriniz e-posta ile gönderilmiştir.');

        } else {
            // Ödeme hatası
            $errorMessage = $payment->getErrorMessage();
            return redirect()->route('checkout.failure')->with('error', $errorMessage); // Başarısız durumda yönlendirme
        }
    }
}
