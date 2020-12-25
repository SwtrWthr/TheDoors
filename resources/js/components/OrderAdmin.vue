<template>
  <v-data-table
    :headers="headers"
    :items="orders"
    sort-by="name"
    sort-desc
    class="elevation-5"
  >
    {{ orders }}
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
                      v-model="editedItem.status"
                      label="Статус"
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
  name: "OrderAdmin",
  data() {
    return {
      tab: 0,
      mdiClose,
      mdiDelete,
      mdiPencil,
      editedIndex: -1,
      editedItem: {
        status: '',
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
          text: 'Имя',
          align: 'center',
          sortable: true,
          value: 'user.first_name',
        },
        {
          text: 'Фамилия',
          align: 'center',
          sortable: true,
          value: 'user.last_name',
        },
        {
          text: 'Номер телефона',
          align: 'center',
          sortable: true,
          value: 'user.phone',
        },
        {
          text: 'Сумма заказа',
          align: 'center',
          sortable: true,
          value: 'total_sum',
        },
        {
          text: 'Статус заказа',
          align: 'center',
          sortable: true,
          value: 'status',
        },
        {
          text: 'Действия',
          align: 'center',
          sortable: false,
          value:'actions'
        }
      ],
      orders: []
    }
  },
  created() {
    this.get_orders()
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Добавить материал' : 'Редактировать материал'
    }
  },
  methods: {
    async get_orders() {
      await axios.get('/api/orders')
        .then(res => {
          console.log(res.data)
          this.orders = res.data.orders
        })
    },

    async add_order() {
      // console.log(this.editedItem)
      await axios.post('/api/orders', this.editedItem)
        .then(res => {
          console.log(res.data)
          this.get_orders()
          this.$emit('snackbar', 'Успешно добавлено!', 'success')
        })
        .catch(e => {
          console.log(e)
          this.$emit('snackbar', 'Ошибочка!', 'error')
        })
    },

    async delete_order(id) {
      await axios.delete('/api/orders/' + id)
        .then(res => {
          console.log(res.data)
          this.$emit('snackbar', 'Удалено!', 'success')
        }).catch(err => {
          this.$emit('snackbar', 'Ошибочка!!', 'error')
        })
    },

    async edit_order(id) {
      await axios.put('/api/orders/' + id, {
        status: this.editedItem.status,
      })
        .then(res => {
          console.log(res.data)
          this.get_orders()
          this.$emit('snackbar', 'Успешно отредактировано!', 'success')
        }).catch(err => {
          this.$emit('snackbar', 'Ошибочка!', 'error')
        })
    },

    editItem(item) {
      console.log(this.orders.indexOf(item))
      this.editedIndex = this.orders.indexOf(item)
      this.editItemID = item.id
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      this.deleteItemID = item.id
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.delete_order(this.deleteItemID)
      this.get_orders()
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
        this.edit_order(this.editItemID)
      } else {
        this.add_order()
      }
      this.close()
    },
  }
}
</script>

<style scoped>

</style>
