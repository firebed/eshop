<?php

namespace Eshop\Models\Product;

enum ProductAction: int
{
    case Create = 1;
    
    case Read = 2;
    
    case Update = 3;
    
    case Delete = 4;
    
    case OrderSubmit = 20;
    
    case OrderCancel = 21;
    
    case OrderReturn = 22;
}
