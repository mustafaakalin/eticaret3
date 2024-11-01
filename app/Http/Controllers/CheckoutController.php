<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Iyzipay\Options;
use App\Models\Order;
use App\Models\OrderItem;
use Iyzipay\Model\Payment;
use Illuminate\Http\Request;
use Iyzipay\Model\BasketItem;
use Illuminate\Support\Facades\Auth;
use Iyzipay\Request\CreatePaymentRequest;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        // Kullanıcının sepetini al
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->with('cartItems.product')->first();
        



        // Sepet yoksa yönlendirme yap
        if (!$cart) {
            return redirect()->route('cart.view')->with('message', 'Sepetinizde ürün yok. Lütfen ürün ekleyin.');
        }

        // Toplam fiyatı hesapla
        $totalPrice = $cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Eğer cartItems boşsa da yönlendirme yapabilirsiniz
        if ($cart->cartItems->isEmpty()) {
            return redirect()->route('cart.view')->with('message', 'Sepetinizde ürün yok. Lütfen ürün ekleyin.');
        }

        // Checkout sayfasına veri geç
        return view('frontend.checkout', compact('cart', 'totalPrice'));

    }

    public function process(Request $request)
    {
        // Validasyon
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255', // Soyadı ekleyin
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'zip_code' => 'required|string',
            'card_number' => 'required|string',
            'expiry_date' => 'required|string',
            'cvc' => 'required|string',
        ]);

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
        $paymentRequest->setPaidPrice($totalPrice); // Ödenecek fiyat (örnek olarak) vergi falan kargo ücreti eklenmek isterse $paymentRequest->setPaidPrice($totalPrice * 1.2);
        $paymentRequest->setCurrency('TRY');
        $paymentRequest->setPaymentChannel('WEB');
        $paymentRequest->setPaymentGroup('PRODUCT');

        // Müşteri bilgileri
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(uniqid());
        $buyer->setName($request->input('name'));
        $buyer->setSurname($request->input('surname')); // Soyadı ekleniyor
        $buyer->setEmail($request->input('email'));
        $buyer->setIdentityNumber('11111111111'); // Örnek kimlik numarası
        $buyer->setRegistrationAddress($request->input('address'));
        $buyer->setIp($request->ip());
        $buyer->setCity($request->input('city')); // Şehir bilgisi ekleniyor
        $buyer->setCountry('Turkey');
        $buyer->setZipCode($request->input('zip_code')); // Posta kodu bilgisi ekleniyor

        $paymentRequest->setBuyer($buyer);

        // Billing Address (Fatura Adresi) bilgilerini ekle
        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($request->input('name') . ' ' . $request->input('surname'));
        $billingAddress->setCity($request->input('city'));
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress($request->input('address'));
        $billingAddress->setZipCode($request->input('zip_code'));
        $paymentRequest->setBillingAddress($billingAddress);
        



        // Shipping Address (Gönderim Adresi) bilgilerini ekle
        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($request->input('name') . ' ' . $request->input('surname')); // İsim ve soyisim
        $shippingAddress->setCity($request->input('city'));
        $shippingAddress->setCountry('Turkey');
        $shippingAddress->setAddress($request->input('address'));
        $shippingAddress->setZipCode($request->input('zip_code'));

        $paymentRequest->setShippingAddress($shippingAddress);

        // Sepetteki her ürün için ayrı bir sepet oluştur
        $basketItems = [];
        foreach ($cart->cartItems as $cartItem) {
            $basketItem = new BasketItem();
            $basketItem->setId('BI_' . $cartItem->product->id);
            $basketItem->setName($cartItem->product->name);
            $basketItem->setCategory1($cartItem->product->category->name); // Kategori adını ayarlayın
            $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL); // Ürün türü
            $basketItem->setPrice($cartItem->product->price * $cartItem->quantity); // Ürün fiyatı
            $basketItems[] = $basketItem;
        }
        
        $paymentRequest->setBasketItems($basketItems);

        // Kart bilgilerini ekleyin
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
        
        // 1. Order oluştur
        $order = Order::create([
            'user_id' => $userId,
            'total_price' => $totalPrice,
            'status' => 'pending',  // İlk durumda sipariş durumu beklemede olabilir
        ]);

        // 2. OrderItems oluştur
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product->id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);
        }

        // 3. Sepet ve sepet öğelerini sil
        $cart->cartItems()->delete(); // Sepet öğelerini sil
        $cart->delete(); // Sepeti sil

         // 4. Sipariş onay e-postasını gönder
        Mail::to($request->input('email'))->send(new OrderConfirmationMail($order));

        return redirect()->route('checkout.success')->with('message', 'Sipariş bilgileriniz  mail gönderilmiştir.');

        } else {
            // Ödeme hatası
            $errorMessage = $payment->getErrorMessage();
            return redirect()->route('checkout.failure')->with('message', $errorMessage); // Başarısız durumda yönlendirme
        }
    }
}
