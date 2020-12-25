<template>
  <v-data-table
    :headers="headers"
    :items="colors"
    sort-by="name"
    sort-desc
    class="elevation-5"
  >
    {{ colors }}
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
              Добавить цвет
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
  name: "ColorAdmin",
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
      ],
      colors: []
    }
  },
  created() {
    this.get_color()
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Добавить цвет' : 'Редактировать цвет'
    }
  },
  methods: {
    async get_color() {
      await axios.get('/api/doors')
        .then(res => {
          this.colors = res.data.colors
        })
    },

    async add_color() {
      // console.log(this.editedItem)
      await axios.post('/api/colors', this.editedItem)
        .then(res => {
          console.log(res.data)
          this.get_color()
          this.$emit('snackbar', 'Успешно добавлено!', 'success')
        })
        .catch(e => {
          console.log(e)
          this.$emit('snackbar', 'Ошибочка!', 'error')
        })
    },

    async delete_color(id) {
      await axios.delete('/api/colors/' + id)
        .then(res => {
          console.log(res.data)
          this.$emit('snackbar', 'Удалено!', 'success')
        }).catch(err => {
          this.$emit('snackbar', 'Ошибочка!!', 'error')
        })
    },

    async edit_color(id) {
      await axios.put('/api/colors/' + id, {
        name: this.editedItem.name,
      })
        .then(res => {
          console.log(res.data)
          this.get_color()
          this.$emit('snackbar', 'Успешно отредактировано!', 'success')
        }).catch(err => {
          this.$emit('snackbar', 'Ошибочка!', 'error')
        })
    },

    editItem(item) {
      console.log(this.colors.indexOf(item))
      this.editedIndex = this.colors.indexOf(item)
      this.editItemID = item.id
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      this.deleteItemID = item.id
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.delete_color(this.deleteItemID)
      this.get_color()
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
        this.edit_color(this.editItemID)
      } else {
        this.add_country()
      }
      this.close()
    },
  }
}
</script>

<style scoped>

</style>
