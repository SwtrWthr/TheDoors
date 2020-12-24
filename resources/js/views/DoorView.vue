<template>
  <div>
    <v-snackbar
      v-model="snackbar"
      top
      right
      color="success"
      text
    >
      {{snackbar_text}}
      <template v-slot:action="{ attrs }">
        <v-btn
          color="success"
          text
          v-bind="attrs"
          @click="snackbar = false"
          fab
        >
          <v-icon>{{mdiClose}}</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
    <v-row v-if="door">
      <v-col
        cols="5"
        class="pa-10"
      >
        <div class="border">
          <v-img :src="'../images/'+door.image" fluid></v-img>
        </div>
      </v-col>
      <v-col
        cols="7"
        class="pa-10"
      >
        <div class="border pa-15 pb-3 bg-white">
          <p class="h3 font-weight-bold mb-0">{{ door.name }}</p>
          <v-rating
            color="warning"
            dense
            hover
            length="5"
            readonly
            size="20"
            :value="4"
          ></v-rating>
          <p class="font-weight-bold text-info my-5"
             style="font-size: 30px">{{ door.price | price_format }}<i>₸</i></p>
          <p style="font-size: 15px" class="my-0"><b>Доступность:</b> {{door.quantity > 0 ? 'В наличии' : 'Нету в наличии'}}</p>
          <p style="font-size: 15px" class="my-0"><b>Брэнд:</b> {{door.brand.name}}</p>
          <p style="font-size: 15px" class="my-0"><b>Страна производства:</b> {{door.country.name}}</p>
          <v-row
            class="align-items-center"
          >
            <v-col
              cols="5"
            >
              <v-text-field
                min="1"
                outlined
                class="mt-6 w-75 centered-input"
                :prepend-icon="mdiPlus"
                :append-outer-icon="mdiMinus"
                @click:prepend="quan++"
                @click:append-outer="quan > 1 ? quan-- : ''"

                dense
                v-model="quan"
              >
              </v-text-field>
            </v-col>
            <v-col cols="7">
              <v-btn
                color="primary"
                :ripple="false"
                @click="addToCart(door.id)"
              >
                Добавить в корзину
              </v-btn>
            </v-col>
          </v-row>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import {mdiPlus, mdiMinus, mdiClose} from '@mdi/js'

export default {
  name: "DoorView",
  data() {
    return {
      quan: 1,
      snackbar_text: '',
      snackbar: false,
      snackbar_color: 'success',
      mdiClose,
      mdiMinus,
      mdiPlus,
      door: ''
    }
  },
  methods: {
    async addToCart(id) {
      await axios.post('/api/add_to_cart', {
        door_id: id,
        quantity: this.quan,
        user_id: this.$store.state.user.id
      }).then(res => {
        console.log(res.data)
        this.$store.dispatch('GET_USER_FROM_API')
        this.snackbar = true
        this.snackbar_text = 'Успешно добавлено в корзину!'
      }).catch(err => {
        console.log(err)
      })
    },
    async getDoor() {
      await axios.get('/api/doors/' + this.$route.params.id)
        .then(res => {
          console.log(res.data)
          this.door = res.data.door
        })
    }
  },
  mounted() {
    this.getDoor()
  },
  filters: {
    price_format(price) {
      return price.toLocaleString()
    }
  }
}
</script>

<style scoped>
/deep/ .centered-input input {
  text-align: center;
}
</style>
