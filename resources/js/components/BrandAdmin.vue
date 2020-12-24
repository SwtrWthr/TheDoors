<template>
  <v-data-table
    :headers="headers"
    :items="brands"
    sort-by="name"
    sort-desc
    class="elevation-5"
  >
    {{ brands }}
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
              Добавить брэнд
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
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      label="Название"
                    ></v-text-field>
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
  name: "BrandAdmin",
  data() {
    return {
      tab: 0,
      mdiClose,
      mdiDelete,
      mdiPencil,
      editedIndex: -1,
      editedItem: {
        name: '',
      },
      deleteItemID: -1,
      editItemID: -1,
      defaultItem: {
        name: '',
      },
      mdiPlaylistEdit,
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: 'Название',
          align: 'center',
          sortable: true,
          value: 'name',
        },
        {
          text: 'Действия',
          align: 'center',
          sortable: false,
          value:'actions'
        }
      ]
    }
  },
  created() {
    this.$store.dispatch('GET_BRANDS_API')
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Добавить брэнд' : 'Редактировать брэнд'
    },
    brands() {
      return this.$store.getters.GET_BRANDS
    },
  },
  methods: {
    async add_brand() {
      // console.log(this.editedItem)
      await axios.post('/api/brands', this.editedItem)
        .then(res => {
          console.log(res.data)
          this.$store.dispatch('GET_BRANDS_API')
          this.$emit('snackbar', 'Успешно добавлено!', 'success')
        })
        .catch(e => {
          console.log(e)
          this.$emit('snackbar', 'Ошибочка!', 'error')
        })
    },

    async delete_brand(id) {
      await axios.delete('/api/brands/' + id)
        .then(res => {
          console.log(res.data)
          this.$emit('snackbar', 'Удалено!', 'success')
        }).catch(err => {
          this.$emit('snackbar', 'Ошибочка!!', 'error')
        })
    },

    async edit_brand(id) {
      await axios.put('/api/brands/' + id, {
        name: this.editedItem.name,
      })
        .then(res => {
          console.log(res.data)
          this.$store.dispatch('GET_BRANDS_API')
          this.$emit('snackbar', 'Успешно отредактировано!', 'success')
        }).catch(err => {
          this.$emit('snackbar', 'Ошибочка!', 'error')
        })
    },

    editItem(item) {
      console.log(this.brands.indexOf(item))
      this.editedIndex = this.brands.indexOf(item)
      this.editItemID = item.id
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      this.deleteItemID = item.id
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.delete_brand(this.deleteItemID)
      this.$store.dispatch('GET_BRANDS_API')
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
        this.edit_brand(this.editItemID)
      } else {
        this.add_brand()
      }
      this.close()
    },
  }
}
</script>

<style scoped>

</style>
