<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
  /**
   * Display the shopping cart
   */
  public function index()
  {
    return view('cart.index');
  }
}
