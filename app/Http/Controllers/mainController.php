<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class mainController extends Controller
{
    public function registerUser(Request $data)
    {
        // Validation rules
        $rules = [
            'fullname' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];

        // Validation messages
        $messages = [
            'fullname.required' => 'Fullname is required.',
            'fullname.unique' => 'This fullname is already taken.',
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'Password is required.',
        ];

        // Validate input
        $validator = Validator::make($data->all(), $rules, $messages);

        // Check validation results
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create new user
        $newUser = new User();
        $newUser->fullname = $data->input('fullname');
        $newUser->email = $data->input('email');
        $newUser->password = $data->input('password');
        $newUser->type = "Customer";

        if ($newUser->save()) {
            return redirect('/login-user')->with('success', 'Your account is ready!');
        } else {
            return redirect()->back()->with('error', 'Failed to create user account. Please try again.');
        }
    }

    public function loginUser(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $customer = User::where('email', $email)
            ->where('password', $password)
            ->first();

        if ($customer) {
            session()->put('id', $customer->id);
            session()->put('type', 'Customer');
            return redirect('/');
        }

        $admin = AdminUser::where('email', $email)
            ->where('password', $password)
            ->first();

        if ($admin) {
            session()->put('id', $admin->id);
            session()->put('type', 'Admin');
            return redirect('/admin-home');
        }

        return redirect('/login-user')->with('fail', 'Login failed! Incorrect email or password');
    }

    public function login()
    {
        return view('pages.login');
    }

    public function post()
    {
        return view('pages.post');
    }

    public function cart()
    {
        $cartItems = DB::table('all_data')
            ->join('carts', function ($join) {
                $join->on('carts.productId', '=', 'all_data.id');
            })
            ->select('all_data.image', 'all_data.details', 'all_data.type', 'all_data.mobile_number', 'carts.*')
            ->where('carts.customerId', session()->get('id'))
            ->get();
        $allCartItems = $cartItems;

        $cartItems = DB::table('flat_for_sale')
            ->join('carts', function ($join) {
                $join->on('carts.productId', '=', 'flat_for_sale.id');
            })
            ->select('flat_for_sale.image', 'flat_for_sale.details', 'flat_for_sale.type', 'flat_for_sale.mobile_number', 'carts.*')
            ->where('carts.customerId', session()->get('id'))
            ->get();
        $allCartItems = $allCartItems->merge($cartItems);

        $cartItems = DB::table('available_data')
            ->join('carts', function ($join) {
                $join->on('carts.productId', '=', 'available_data.id');
            })
            ->select('available_data.image', 'available_data.details', 'available_data.type', 'available_data.mobile_number', 'carts.*')
            ->where('carts.customerId', session()->get('id'))
            ->get();
        $allCartItems = $allCartItems->merge($cartItems);

        $cartItems = DB::table('land_sale_table')
            ->join('carts', function ($join) {
                $join->on('carts.productId', '=', 'land_sale_table.id');
            })
            ->select('land_sale_table.image', 'land_sale_table.details', 'land_sale_table.type', 'land_sale_table.mobile_number', 'carts.*')
            ->where('carts.customerId', session()->get('id'))
            ->get();
        $allCartItems = $allCartItems->merge($cartItems);

        return view('pages.cart', compact('allCartItems'));
    }

    public function register()
    {
        return view('pages.register');
    }

    public function logout()
    {
        session()->forget('id');
        session()->forget('type');
        return view('pages.home');
    }

    public function deleteCartItem($id)
    {
        $Item = Cart::find($id);
        $Item->delete();
        return redirect()->back()->with('success', 'item deleted from cart!');
    }

    public function addToCart(Request $data)
    {
        if (session()->has('id')) {
            $item = new Cart;
            $item->productId = $data->input('productId');
            $item->quantity = $data->input('quantity');
            $item->type = $data->input('type');
            $item->address = $data->input('address');
            $item->customerId = session()->get('id');
            $item->save();
            return redirect()->back()->with('success', 'item added to cart!');
        } else  return redirect('/login-user')->with('fail', 'Login to your account fisrt!');
    }

    public function updateQuantity(Request $request)
    {
        $itemId = $request->itemId;
        $newQuantity = $request->newQuantity;

        $cartItem = Cart::find($itemId);
        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Quantity updated successfully');
    }

    public function clearCart()
    {
        $customerId = session()->get('id');
        $cartItemsCount = Cart::where('customerId', $customerId)->count();

        if ($cartItemsCount > 0) {
            Cart::where('customerId', $customerId)->delete();
            return redirect()->back()->with('success', 'Cart cleared successfully!');
        } else {
            return redirect()->back()->with('fail', 'Your cart is already empty!');
        }
    }

    public function PurchaseButton($id)
    {

        $cartItems = Cart::where('customerId', $id)->get();

        if ($cartItems->isNotEmpty()) {
            foreach ($cartItems as $cartItem) {
                $purchase = new Purchase();
                $purchase->productId = $cartItem->productId;
                $purchase->customerId = $cartItem->customerId;
                $purchase->quantity = $cartItem->quantity;
                $purchase->address = $cartItem->address;
                $purchase->date = now();
                $purchase->save();
            }

            Cart::where('customerId', $id)->delete();

            return redirect()->back()->with('success', 'Purchase confirmed. We will reach you soon');
        } else {
            return redirect()->back()->with('fail', 'No items found for you.');
        }
    }

    public function PurchaseShow($c_id)
    {
        $purchaseItems = Purchase::where('customerId', $c_id)->get();
        return view('pages.purchaseDetails', compact('purchaseItems'));
    }
}
