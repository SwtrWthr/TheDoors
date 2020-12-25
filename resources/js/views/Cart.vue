<template>
  <div>
    <v-dialog
      v-model="dialog"
      persistent
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">
          Статус заказа
        </v-card-title>
        <v-card-text>
          Ваш заказ успешно оформлен!
        </v-card-text>
        <v-card-actions>
          <div
            class="text-center w-100"
          >
            <v-spacer></v-spacer>
            <v-btn
              color="success"
              @click="dialog = false"
            >
              Окей
            </v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar
      v-model="snackbar"
      top
      right
      light
      text
      :color="snackbar_color"
      :timeout="4500"
    >
      {{ snackbar_text }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="primary"
          text
          v-bind="attrs"
          @click="snackbar = false"
        >
          <v-icon light>{{ mdiClose }}</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
    <template v-if="!(cart && cart.length>0)">
      <v-row
        justify="center"
        align-content="center"
        style="min-height: 74vh;"
      >
        <div class="d-flex flex-column justify-center align-center">
          <v-icon x-large>{{mdiCartVariant}}</v-icon>
          <p class="pt-5 grayLight--text">Ваша корзина пуста!</p>
        </div>
      </v-row>
    </template>
    <template v-else>
      <v-row>
        <v-col
          cols="12"
        >
          <v-card
            v-for="(door, idx) in cart"
            :key="door.id"
            class="pa-3 ma-2"
          >
            <v-row
              class="align-items-center"
            >
              <v-col
                cols="2"
              >
                <v-img
                  :src="'../images/'+door.door.image" fluid
                ></v-img>
              </v-col>
              <v-col
                cols="4"
              >
                <p class="h3 m-0 p-0">{{door.door.name}}</p>
              </v-col>
              <v-col
                cols="2"
                class="align-items-center"
              >
                <v-text-field
                  min="1"
                  outlined
                  class="mt-6 w-75 centered-input"
                  :prepend-icon="mdiPlus"
                  :append-outer-icon="mdiMinus"
                  @click:prepend="door.door.quantity > door.quantity ? door.quantity++ : ''"
                  @click:append-outer="door.quantity > 1 ? door.quantity-- : ''"

                  dense
                  v-model="door.quantity"
                >

                </v-text-field>
              </v-col>
              <v-col
                cols="2"
              >
                <p class="h3 m-0 p-0 text-info">{{parseInt(door.door.price)*door.quantity}} <i>₸</i></p>
              </v-col>
              <v-col
                cols="2"
              >
                <v-icon
                  large
                  class="float-right mr-5"
                  @click="delete_item_from_cart(door.id, idx)"
                  style="cursor: pointer"
                >
                  {{mdiClose}}
                </v-icon>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
      <v-row class="justify-content-end">
        <v-col
          cols="auto"
        >
          <p class="h3">Total sum: </p>
        </v-col>
        <v-col
          cols="auto"
        >
          <p class="h3 font-weight-bold text-info"> {{getTotalSum()}} <i>₸</i></p>
        </v-col>
      </v-row>
      <v-row class="justify-content-end">
        <v-col
          cols="12"
          md="3"
        >
          <v-select
            v-model="selected"
            :items="order_types"
            item-text="title"
            item-value="id"
            label="Тип заказа"
            dense
            outlined
          ></v-select>
        </v-col>
      </v-row>
      <v-row class="justify-content-end">
        <v-btn
          style="background-color: #17a2b8; color:white; font-size: 16px"
          :ripple="false"
          class="text-capitalize mr-4 px-15"
          large
          @click="make_order"
        >
          Оформить заказ
        </v-btn>
      </v-row>
    </template>
  </div>
</template>

<script>
import {mdiCartVariant, mdiClose, mdiPlus, mdiMinus} from '@mdi/js'

export default {
  name: "Cart",
  data() {
    return {
      selected: '',
      snackbar: false,
      snackbar_color: '',
      snackbar_text: '',
      order_types: [
        {
          id: 0,
          title: 'Самовывоз'
        },
        {
          id: 1,
          title: 'Доставка (+1000тг)'
        }
      ],
      mdiMinus,
      mdiPlus,
      mdiClose,
      mdiCartVariant,
      dialog: false
    }
  },
  mounted() {

  },
  methods: {
    async make_order() {
      await axios.post('/api/orders', {
        user_id: this.$store.state.user.id,
        type: this.selected.length > 0 ? parseInt(this.selected) : 0,
        total_sum: this.getTotalSum()
      })
        .then(res => {
          console.log(res.data)
          this.dialog = true
        })
    },

    async delete_item_from_cart(id, idx) {
      await axios.delete('/api/delete_from_cart/' + id)
        .then(res => {
          console.log(res.data)
          this.snackbar = true
          this.snackbar_color = 'success'
          this.snackbar_text = 'Удалено!'
          this.cart.splice(idx, 1)
        })
    },
    getTotalSum() {
      let sum = 0;
      for(let i=0;i<this.cart.length;i++) {
        sum += (this.cart[i].quantity*this.cart[i].door.price)
      }
      return sum + (this.selected === 1 ? 1000 : 0)
    }
  },
  computed: {
    cart() {
      return this.$store.getters.GET_CART
    }
  }
}
</script>

<style scoped>
/deep/ .centered-input input {
  text-align: center;
}
</style>
