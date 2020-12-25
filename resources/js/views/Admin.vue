<template>
  <div class="mx-6 mt-5">
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
    <v-tabs
      centered
      v-model="tab"
    >
      <v-tab>Двери</v-tab>
      <v-tab>Брэнды</v-tab>
      <v-tab>Страны</v-tab>
      <v-tab>Цвета</v-tab>
      <v-tab>Материалы</v-tab>
      <v-tab>Заказы</v-tab>
    </v-tabs>
    <v-tabs-items
      v-model="tab"
    >
      <v-tab-item
        :value="0"
      >
        <DoorAdmin
          @snackbar="(text, color) => (
            this.snackbar_text = text,
            this.snackbar = true,
            this.snackbar_color = color
          )"
        ></DoorAdmin>
      </v-tab-item>
      <v-tab-item :value="1">
        <BrandAdmin></BrandAdmin>
      </v-tab-item>
      <v-tab-item :value="2">
        <CountryAdmin></CountryAdmin>
      </v-tab-item>
      <v-tab-item :value="3">
        <ColorAdmin></ColorAdmin>
      </v-tab-item>
      <v-tab-item :value="4">
        <MaterialAdmin></MaterialAdmin>
      </v-tab-item>
      <v-tab-item :value="5">
        <OrderAdmin></OrderAdmin>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>

<script>
import {mdiPlaylistEdit, mdiPencil, mdiDelete, mdiClose} from '@mdi/js'
import DoorAdmin from "../components/DoorAdmin";
import BrandAdmin from "../components/BrandAdmin";
import CountryAdmin from "../components/CountryAdmin";
import MaterialAdmin from "../components/MaterialAdmin";
import OrderAdmin from "../components/OrderAdmin";
import ColorAdmin from "../components/ColorAdmin";
export default {
  name: "Admin",
  components: {ColorAdmin, OrderAdmin, MaterialAdmin, BrandAdmin, DoorAdmin, CountryAdmin},
  data() {
    return {
      tab: 0,
      mdiClose,
      snackbar: false,
      snackbar_text: '',
      snackbar_color: '',
      mdiDelete,
      mdiPencil,
      editedIndex: -1,
      editedItem: {
        name: '',
        price: 0,
        quantity: 0,
        garant: 0,
        size: 0,
        description: '',
        brand_id: '',
        country_id: '',
        image: []
      },
      deleteItemID: -1,
      editItemID: -1,
      defaultItem: {
        name: '',
        price: 0,
        quantity: 0,
        garant: 0,
        size: 0,
        description: '',
        brand_id: '',
        country_id: '',
        image: []
      },
      mdiPlaylistEdit,
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: 'Название',
          align: 'start',
          sortable: false,
          value: 'name',
          width: '130'
        },
        {text: 'Цена', value: 'price'},
        {text: 'Кол', value: 'quantity', width: '30'},
        {text: 'Гар-ия', value: 'garant', width: '30'},
        {text: 'Размер', value: 'size'},
        {text: 'Описание', value: 'description', width: '430', sortable: false},
        {text: 'Брэнд', value: 'brand.name'},
        {text: 'Страна', value: 'country.name'},
        {text: 'Дей-вия', value: 'actions', sortable: false},
      ]
    }
  },
  created() {
    this.$store.dispatch('GET_DOORS_API')
    this.$store.dispatch('GET_BRANDS_API')
    this.$store.dispatch('GET_COUNTRIES_API')
  },
  computed: {
    doors() {
      return this.$store.getters.GET_DOORS
    },
    formTitle() {
      return this.editedIndex === -1 ? 'Добавить товар' : 'Редактировать товар'
    },
    brands() {
      return this.$store.getters.GET_BRANDS
    },
    countries() {
      return this.$store.getters.GET_COUNTRIES
    }
  },
  methods: {
    async add_door() {
      this.editedItem.image = this.editedItem.image.name
      // console.log(this.editedItem)
      await axios.post('/api/doors', this.editedItem)
        .then(res => {
          console.log(res.data)
          this.$store.dispatch('GET_DOORS_API')
          this.snackbar = true
          this.snackbar_text = 'Успешно добавлено!'
          this.snackbar_color = 'success'
        })
        .catch(e => {
          console.log(e)
          this.snackbar = true
          this.snackbar_text = 'Oops!'
          this.snackbar_color = 'error'
        })
    },

    async delete_door(id) {
      await axios.delete('/api/doors/' + id)
        .then(res => {
          console.log(res.data)
          this.snackbar = true
          this.snackbar_text = 'Успешно удалено!'
          this.snackbar_color = 'success'
        }).catch(err => {
          this.snackbar = true
          this.snackbar_text = 'Oops!'
          this.snackbar_color = 'error'
        })
    },

    async edit_door(id) {
      await axios.put('/api/doors/' + id, {
        name: this.editedItem.name,
        price: this.editedItem.price,
        quantity: this.editedItem.quantity,
        garant: this.editedItem.garant,
        size: this.editedItem.size,
        description: this.editedItem.description,
        brand_id: this.editedItem.brand.id ? this.editedItem.brand.id : this.editedItem.brand,
        country_id: this.editedItem.country.id ? this.editedItem.country.id : this.editedItem.country,
        image: this.editedItem.image.name ? this.editedItem.image.name : this.editedItem.image
      })
        .then(res => {
          console.log(res.data)
          this.$store.dispatch('GET_DOORS_API')
          this.snackbar = true
          this.snackbar_text = 'Успешно отредактировано!'
          this.snackbar_color = 'success'
        }).catch(err => {
          this.snackbar = true
          this.snackbar_text = 'Oops!'
          this.snackbar_color = 'error'
        })
    },

    editItem(item) {
      console.log(this.doors.indexOf(item))
      this.editedIndex = this.doors.indexOf(item)
      this.editItemID = item.id
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      this.deleteItemID = item.id
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.delete_door(this.deleteItemID)
      this.$store.dispatch('GET_DOORS_API')
      this.closeDelete()
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save() {
      if (this.editedIndex > -1) {
        this.edit_door(this.editItemID)
      } else {
        this.add_door()
      }
      this.close()
    },
  }
}
</script>

<style scoped>

</style>
