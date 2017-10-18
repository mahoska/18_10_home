<template>
<div class="cart">
  <div class="bg-danger" id="err">{{isDelCartRec}}</div>
  <table class="table table-bordered">
    <tr>
      <th>Active</th>
      <th>Book name</th>
      <th>Price</th>
      <th>Count</th>
      <th>Sum</th>
    </tr>
    <tr v-for="book in cart" :key="book.id">
      <td>
        <div class="checkbox">
          <label>
            <input type="checkbox" :value="book.id"  :id="book.id" checked  @change="checkBook(book.id,book.count)">
          </label>
        </div>
      </td>
      <td>
          <router-link  :to="'/book/'+book.id" class="btn btn-auth" >
            {{ book.name }}
          </router-link>
      </td>
      <td>{{book.price}} GRN</td>
      <td>
        <div>
						<div class="btn min_btn inline" id="minus" @click="changeCount(false,book.id)">-</div>
						<div class="count inline">{{book.count}}</div>
						<div class="btn min_btn  inline" id="plus" @click="changeCount(true,book.id)">+</div>
				</div>
      </td>
      <td></td>
    </tr>
  </table>
  
</div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'cart',
  data () {
    return {
      cart: [],
      isDelCartRec: ""
    }
  },
  created(){
    var self = this
    

      if(localStorage['hash_log'] && localStorage['user']){
        axios.get('http://localhost:88/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
        //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
              .then(function (response) {
                var client_id = parseInt(response.data.data)
                if(client_id>0){
                  axios.get('http://localhost:88/BOOK_SHOP/client/api/cart/'+ client_id +'/1', self.config)
                  //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/cart/'+ client_id, self.config)
                  .then(function (response) {
                    console.log(response.data.data)
                    self.cart = response.data.data
                    
                  });
                } 
              });
      }

      
  },
  computed:{
   
  },
  methods:{
   checkBook: function(book_id,book_count){
      var self = this
      this.isDelCartRec = ""
      var el = document.getElementById(book_id)
      if(!el.checked){
        var del = -1
        for(var i=0; i<this.cart.length; i++){
          if(this.cart[i].id==book_id){
             del=i
            if(localStorage['hash_log'] && localStorage['user']){
            axios.get('http://localhost:88/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
            //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
              .then(function (response) {
                var client_id = parseInt(response.data.data)
                if(client_id>0){ 
                  if (confirm("Are you sure you want to cancel this order?")) {
                      axios.delete('http://localhost:88/BOOK_SHOP/client/api/cart/'+book_id, self.config)
                      //axios.delete('http://192.168.0.15/~user15/BOOK_SHOP/client/api/cart/'+book_id, self.config)
                          .then(function (response) {
                            //console.log(self.cart);
                          if(response.data.data==1){
                              self.cart.splice(del, 1);
                              self.$emit('changeCountTotal',book_count,false)
                          }
                      })
                      .catch(function (error) {
                        console.log(error);
                      });
                
                  } 
                }else{
                  self.isDelCartRec = "for this action you need to register"
                    setTimeout(function () {
                      self.isDelCartRec = ""
                    },1500);
                  self.$emit('setlogout')     
                }
                })
                .catch(function (error) {
                  self.$emit('setlogout') 
                });
            }
          }
        }
      }
    },
    changeCount(flag,book_id){
      for(var i=0; i<this.cart.length; i++){
        if(this.cart[i].id==book_id){
          if(flag){
            this.cart[i].count++
            this.$emit('changeCountTotal', 1, true )
          }
          else{
            if(this.cart[i].count >= 2){
              this.cart[i].count--
              this.$emit('changeCountTotal', 1, false )
            }
          }
        }
      }
    }

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.cart{
min-height: 500px;
}

.inline{
	display:inline-block;
}

.min_btn{
  background-color:#B0C9E2;
	color: black;
}

table{
			font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
      margin: 50px auto;
			font-size: 14px;
			background: white;
      color: black;
			max-width: 70%;
			width: 70%;
			border-collapse: collapse;
			text-align: left;
			/*background: url("../assets/blurry.jpg") no-repeat;*/
			/*background-position: 100% 55px;*/
		}
		th {
		  font-weight: normal;
		  color: #339;
		  padding: 10px 12px;
		}
		td {
		 background-image: url("../assets/back.png");
		 filter:alpha(opacity=60);
		 opacity:0.6;
		  color: #669;
		  border-top: 1px solid white;
		  padding: 10px 12px;
		}
		tr:hover td {
		  /*background-image: url("back.png");*/
		  background: transparent;/*Устанавливает прозрачный фон. */
		}
</style>
