<template>
  <v-data-table
    :headers="headers"
    :items="doors"
    sort-by="created_at"
    sort-desc
    class="elevation-5"
  >
    {{ doors }}
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>
          <v-icon large>{{ mdiPlaylistEdit }}</v-icon>
        </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
          persistent
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >
              Добавить товар
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      label="Название"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                  >
                    <v-text-field
                      v-model="editedItem.price"
                      label="Цена"
                      type="number"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.quantity"
                      label="Количество"
                      type="number"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.size"
                      label="Размер"
                      type="text"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.garant"
                      label="Гарантия"
                      type="number"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                  >
                    <v-select
                      v-if="editedIndex > -1"
                      v-model="editedItem.country"
                      :items="countries"
                      item-text="name"
                      item-value="id"
                      label="Страна"
                    ></v-select>
                    <v-select
                      v-else
                      v-model="editedItem.country_id"
                      :items="countries"
                      item-text="name"
                      item-value="id"
                      label="Страна"
                    ></v-select>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                  >
                    <v-select
                      v-if="editedIndex === -1"
                      v-model="editedItem.brand_id"
                      :items="brands"
                      item-text="name"
                      item-value="id"
                      label="Брэнд"
                    ></v-select>
                    <v-select
                      v-else
                      v-model="editedItem.brand"
                      :items="brands"
                      item-text="name"
                      item-value="id"
                      label="Брэнд"
                    ></v-select>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col
                    cols="12"
                  >
                    <v-textarea
                      v-model="editedItem.description"
                      label="Описание"
                    ></v-textarea>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <p class="caption mb-0">Цвет</p>
                    <v-chip-group
                      v-model="editedItem.colors"
                      active-class="primary--text"
                      column
                      multiple
                    >
                      <v-chip
                        v-for="color in colors"
                        :key="color.id"
                        :ripple="false"
                        :value="color.id"
                        active
                      >
                        {{ color.name }}
                      </v-chip>
                    </v-chip-group>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <p class="caption mb-0">Материал</p>
                    <v-chip-group
                      v-model="editedItem.materials"
                      active-class="primary--text"
                      column
                      multiple
                    >
                      <v-chip
                        v-for="material in materials"
                        :key="material.id"
                        :ripple="false"
                        :value="material.id"
                      >
                        {{ material.name }}
                      </v-chip>
                    </v-chip-group>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col
                    cols="12"
                  >
                    <v-file-input
                      v-model="editedItem.image"
                      show-size
                      truncate-length="50"
                      label="Изображение"
                      @change="imageChange"
                      :value="editedItem.image.name"
                    ></v-file-input>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="danger darken-1"
                text
                @click="close"
              >
                Отмена
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="save"
              >
                Сохранить
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="630px">
          <v-card>
            <v-card-title class="headline">Вы действительно хотите удалить данный товар?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="black darken-1" text @click="closeDelete">Отмена</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        {{ mdiPencil }}
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        {{ mdiDelete }}
      </v-icon>
    </template>
  </v-data-table>
</template>

<script>
import {mdiClose, mdiDelete, mdiPencil, mdiPlaylistEdit} from "@mdi/js";

export default {
  name: "DoorAdmin",
  data() {
    return {
      tab: 0,
      mdiClose,
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
        image: [],
        colors: [],
        materials: []
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
        image: [],
        colors: [],
        materials: []
      },
      mdiPlaylistEdit,
      imageUpload: '',
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
        {text: 'Кол', value: 'quantity', width: '25'},
        {text: 'Гар-ия', value: 'garant', width: '25'},
        {text: 'Размер', value: 'size', width: '25'},
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
    this.$store.dispatch('GET_COLORS_API')
    this.$store.dispatch('GET_MATERIALS_API')
    this.$store.dispatch('GET_COUNTRIES_API')
  },
  computed: {
    doors() {
      return this.$store.getters.GET_DOORS
    },
    formTitle() {
      return this.editedIndex === -1 ? 'Добавить товар' : 'Редактировать товар'
    },
    colors() {
      return this.$store.getters.GET_COLORS
    },
    materials() {
      return this.$store.getters.GET_MATERIALS
    },
    brands() {
      return this.$store.getters.GET_BRANDS
    },
    countries() {
      return this.$store.getters.GET_COUNTRIES
    }
  },
  methods: {
    imageChange(e) {
      console.log(e)
      var fileRead = new FileReader()
      if(e) {
        fileRead.readAsDataURL(e)
      }
      fileRead.onload = (e) => {
        this.imageUpload = e.target.result
      }
      console.log(this.editedItem)
    },
    async add_door() {
      this.editedItem.image = this.imageUpload
      // console.log(this.editedItem)
      await axios.post('/api/doors', this.editedItem)
        .then(res => {
          console.log(res.data)
          this.$store.dispatch('GET_DOORS_API')
          this.$emit('snackbar', 'Успешно добавлено!', 'success')
        })
        .catch(e => {
          console.log(e)
          this.$emit('snackbar', 'Ошибочка!', 'error')
        })
    },

    async delete_door(id) {
      await axios.delete('/api/doors/' + id)
        .then(res => {
          console.log(res.data)
          this.$emit('snackbar', 'Удалено!', 'success')
        }).catch(err => {
          this.$emit('snackbar', 'Ошибочка!!', 'error')
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
        image: this.imageUpload ? this.imageUpload : this.editedItem.image,
        colors: this.editedItem.colors,
        materials: this.editedItem.materials
      })
        .then(res => {
          console.log(res.data)
          this.$store.dispatch('GET_DOORS_API')
          this.$emit('snackbar', 'Успешно отредактировано!', 'success')
        }).catch(err => {
          this.$emit('snackbar', 'Ошибочка!', 'error')
        })
    },

    editItem(item) {
      console.log(this.doors.indexOf(item))
      this.editedIndex = this.doors.indexOf(item)
      this.editItemID = item.id
      this.editedItem = Object.assign({}, item)
      this.editedItem.colors = item.colors.map(color=>color.id)
      this.editedItem.materials = item.materials.map(color=>color.id)
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
