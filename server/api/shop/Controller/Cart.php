<?php

class Cart extends Controller
{
    public function getAllCart($params = false)
    {
        
    }
    
    public function getItemCart($params)
    {
        if(count($params) != 1 || !((int)$params[0]>0)){
            return ['status'=>400, 'clientCode'=>'001'];
        }
        $model = new CartModel();
        return $model->getCountCartByClient($params[0]);
    }
    
    public function getDataCart($params){
        if(count($params) != 2 || !((int)$params[0]>0) || (int)$params[1]!= 1){
            return ['status'=>400, 'clientCode'=>'001'];
        }
        $model = new CartModel();
        $res=  $model->getCartByClient($params[0]);
        $cart = $res['data'];
        if($res['status']==200 && count($cart)>0){
            foreach($cart as &$val){
                if($val['book_discount']>0 && $val['book_discount']<100){
                    $val['price'] = round(($val['price'] - $val['price']*$val['book_discount']/100), 2);
                }
            }
             return ['status'=>200,'data'=>$cart];
        }else 
            return $cart;
       
    }
    
    public function postCart($params)
    {
        if(count($params) != 3){
            return ['status'=>400, 'clientCode'=>'0001'];
        }
        foreach($params as $key=>$val){
            if((int)$val>0) continue;
            else return  ['status'=>400, 'clientCode'=>'0001'];
        }
        $model = new CartModel();
        //if book exists
        $count = $model->getCountBook(['book_id'=>$params['book_id'],'client_id'=>$params['client_id']]); 
        if($count !=null && $count > 0){
            $params['count'] += $count;
            return $model->updateCart($params); 
        }else{
            return $model->createCart($params); 
        }
    }
    

    public function putCart($params)
    {

    }
    
     public function deleteCart($params)
    {
        if(count($params) != 1 || !((int)$params[0]>0)){
            return ['status'=>400, 'clientCode'=>'001'];
        }
        $model = new CartModel();
        return $model->deleteCart($params[0]); 
    }
}

