<template>
  <div>
    <v-app class="bg-light">
      <v-snackbar
        v-model="snackbar"
        top
        right
        color="success"
        text
      >
        {{ snackbar_text }}
        <template v-slot:action="{ attrs }">
          <v-btn
            color="success"
            text
            v-bind="attrs"
            @click="snackbar = false"
          >
            <v-icon>{{ mdiClose }}</v-icon>
          </v-btn>
        </template>
      </v-snackbar>
      <template v-if="loading">
        <v-skeleton-loader
          class="mx-auto"
          width="100%"
          height="100%"
          type="image"
        ></v-skeleton-loader>
        <v-skeleton-loader
          class="mx-auto"
          width="100%"
          height="100%"
          type="image"
        ></v-skeleton-loader>
        <v-row>
          <b-row class="d-flex justify-content-md-between">
            <b-col xl="3" class="font-weight-bold p-4">
              <div>
                <b-row align-h="around">
                  <b-col>
                    <hr>
                  </b-col>
                  <b-col class="px-0 text-center" style="letter-spacing: 0.1em; font-size: 18px">
                    ПОИСК
                  </b-col>
                  <b-col>
                    <hr>
                  </b-col>
                </b-row>
                <b-row class="mt-3 mx-0">
                  <b-col cols="12" class="px-0">
                    <v-text-field
                      :append-icon="mdiMagnify"
                      v-model="search"
                      outlined
                      label="search"
                      dense
                    >
                    </v-text-field>
                  </b-col>
                </b-row>
              </div>
              <div class="mt-2">
                <b-row align-h="around">
                  <b-col>
                    <hr>
                  </b-col>
                  <b-col class="px-0 text-center" style="letter-spacing: 0.1em; font-size: 18px">
                    БРЭНДЫ
                  </b-col>
                  <b-col>
                    <hr>
                  </b-col>
                </b-row>
                <b-row class="mx-0 justify-content-md-between">
                  <v-skeleton-loader
                    type="article"
                    width="100%"
                    height="100%"
                  ></v-skeleton-loader>
                </b-row>
              </div>
              <div class="mt-4">
                <b-row align-h="around">
                  <b-col>
                    <hr>
                  </b-col>
                  <b-col class="px-0 text-center" style="letter-spacing: 0.1em; font-size: 18px">
                    СТРАНА
                  </b-col>
                  <b-col>
                    <hr>
                  </b-col>
                </b-row>
                <b-row class="mx-0 justify-content-md-between">
                  <v-skeleton-loader
                    type="article"
                    width="100%"
                    height="100%"
                  ></v-skeleton-loader>
                </b-row>
              </div>
            </b-col>
            <b-col xl="9" class="text-center font-weight-bold p-4">
              <b-row align-h="around">
                <b-col xs="5">
                  <hr>
                </b-col>
                <b-col xs="2" style="letter-spacing: 0.1em; font-size: 18px">
                  ДВЕРИ
                </b-col>
                <b-col xs="5">
                  <hr>
                </b-col>
              </b-row>
              <b-row>
                <b-col lg="4" v-for="door in 9" :key="door" class="text-center p-3">
                  <v-skeleton-loader
                    type="image, article"
                  ></v-skeleton-loader>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
        </v-row>
      </template>
      <template v-else>
        <c-carousel
          :doors="doors"
        ></c-carousel>
        <b-row class="d-flex justify-content-md-between">
          <b-col xl="3" class="font-weight-bold p-4">
            <div id="main-home">
              <b-row align-h="around">
                <b-col>
                  <hr>
                </b-col>
                <b-col class="px-0 text-center" style="letter-spacing: 0.1em; font-size: 18px">
                  ПОИСК
                </b-col>
                <b-col>
                  <hr>
                </b-col>
              </b-row>
              <b-row class="mt-3 mx-0">
                <b-col cols="12" class="px-0">
                  <v-text-field
                    :append-icon="mdiMagnify"
                    v-model="search"
                    outlined
                    label="search"
                    dense
                  >
                  </v-text-field>
                </b-col>
              </b-row>
            </div>
            <div class="mt-2">
              <b-row align-h="around">
                <b-col>
                  <hr>
                </b-col>
                <b-col class="px-0 text-center" style="letter-spacing: 0.1em; font-size: 18px">
                  БРЭНДЫ
                </b-col>
                <b-col>
                  <hr>
                </b-col>
              </b-row>
              <b-row
                v-for="(brand, idx) in brands"
                :key="idx"
                class="mx-0">
                <b-col
                  class="p-0"
                  cols="12"
                >
                  <v-checkbox
                    v-model="selected_brands"
                    :value="brand.id"
                    :ripple="false"
                    class="ma-0 pa-0"
                    dense
                    style="height: 25px"
                  >
                    <template v-slot:label>
                      <p
                        class="ma-0 mt-2"
                        style="color: black"
                      >
                        {{ brand.name }}
                        <span
                          class="text-muted">({{
                            currentFilter === 'brand' || currentFilter === '' ? doors.filter(door => door.brand.id == brand.id).length : filtered_doors.filter(door => door.brand.id == brand.id).length
                          }})
                    </span>
                      </p>
                    </template>
                  </v-checkbox>
                </b-col>
              </b-row>
            </div>
            <div class="mt-4">
              <b-row align-h="around">
                <b-col>
                  <hr>
                </b-col>
                <b-col class="px-0 text-center" style="letter-spacing: 0.1em; font-size: 18px">
                  СТРАНА
                </b-col>
                <b-col>
                  <hr>
                </b-col>
              </b-row>
              <b-row
                v-for="(country, idx) in countries"
                :key="idx"
                class="mx-0"
              >
                <b-col
                  class="p-0"
                  cols="12"
                >
                  <v-checkbox
                    :ripple="false"
                    v-model="selected_countries"
                    :value="country.id"
                    class="ma-0 pa-0"
                    dense
                    style="height: 25px"
                  >
                    <template v-slot:label>
                      <p
                        class="ma-0 mt-2"
                        style="color: black"
                      >
                        {{ country.name }}
                        <span
                          class="text-muted"
                        >({{
                            currentFilter === 'country' || currentFilter === '' ? doors.filter(door => door.country.id == country.id).length : filtered_doors.filter(door => door.country.id == country.id).length
                          }})
                        </span>
                      </p>
                    </template>
                  </v-checkbox>
                </b-col>
              </b-row>
            </div>
            <!--                <div class="mt-4">-->
            <!--                    <b-row align-h="around">-->
            <!--                        <b-col>-->
            <!--                            <hr>-->
            <!--                        </b-col>-->
            <!--                        <b-col class="px-0 text-center" style="letter-spacing: 0.1em; font-size: 18px">-->
            <!--                            МАТЕРИАЛ-->
            <!--                        </b-col>-->
            <!--                        <b-col>-->
            <!--                            <hr>-->
            <!--                        </b-col>-->
            <!--                    </b-row>-->
            <!--                    <b-row v-for="material in GET_MATERIALS" :key="material.id" class="mx-0 justify-content-md-between">-->
            <!--                        <b-col>-->
            <!--                            <b-checkbox v-model="filtered_materials" :value="material.id"-->
            <!--                                        class="float-left brand-checkbox shadow-none">{{ material.name }}-->
            <!--                            </b-checkbox>-->
            <!--                        </b-col>-->
            <!--                        <b-col>-->
            <!--                            <span-->
            <!--                                class="float-right text-muted">({{ GET_DOORS.filter(door => door.country_id == country.id).length }})</span>-->
            <!--                        </b-col>-->
            <!--                    </b-row>-->
            <!--                </div>-->
          </b-col>
          <b-col xl="9" class="text-center font-weight-bold p-4">
            <b-row align-h="around">
              <b-col xs="5">
                <hr>
              </b-col>
              <b-col xs="2" style="letter-spacing: 0.1em; font-size: 18px">
                ДВЕРИ
              </b-col>
              <b-col xs="5">
                <hr>
              </b-col>
            </b-row>
            <transition-group
              name="fade"
              mode="out-in"
              tag="b-row"
            >
              <b-col
                lg="4"
                v-for="door in paginateDoors[currentIndex-1]"
                :key="door.id"
                class="text-center
                p-3"
              >
                <div
                  class="border bg-white"
                >
                  <b-img :src="'../images/'+door.image" fluid class="mt-3 mb-2"></b-img>
                  <p class="font-weight-bold text-info mb-0"
                     style="font-size: 28px">{{ door.price | price_format }}<i>₸</i></p>
                  <router-link
                    tag="p"
                    style="cursor:pointer"
                    :to="{
                      name: 'DoorView',
                      params: {
                        id: door.id
                      }
                    }"
                  >{{ door.name }}
                  </router-link>
                  <v-btn
                    class="mb-3"
                    color="primary"
                    :ripple="false"
                    @click="addToCart(door.id)"
                  >Добавить в корзину
                  </v-btn>
                </div>
              </b-col>
            </transition-group>
            <v-row
              class="text-center"
            >
              <v-col
                cols="12"
              >
                <v-pagination
                  v-model="currentIndex"
                  v-scroll-to="'#main-home'"
                  :length="paginateDoors.length"
                  circle
                ></v-pagination>
              </v-col>
            </v-row>
          </b-col>
        </b-row>
      </template>
    </v-app>
  </div>
</template>

<script>
import {mdiMagnify, mdiClose} from '@mdi/js'

export default {
  name: "Home",
  data() {
    return {
      mdiClose,
      snackbar: false,
      snackbar_text: '',
      loading: false,
      mdiMagnify,
      search: '',
      currentFilter: '',
      selected_brands: [],
      filtered_brands: [],
      filtered_doors: [],
      selected_countries: [],
      filtered_countries: [],
      doors: [],
      brands: [],
      colors: [],
      materials: [],
      countries: [],
      currentIndex: 1
      // filtered_materials: []
    }
  },
  mounted() {
    this.loading = true
    this.getDoors()
      .then(() => {
        this.loading = false
      })
    // this.GET_BRANDS_API()
    //   .then(() => {
    //     this.filtered_brands = [...this.GET_BRANDS]
    //   })
    // this.GET_COUNTRIES_API()
    //   .then(() => {
    //     this.filtered_countries = [...this.GET_COUNTRIES]
    //   })
    // this.GET_MATERIALS_API()
    // this.GET_DOORS_API()
    //   .then(() => {
    //     this.filtered_doors = [...this.GET_DOORS]
    //     this.$store.commit('SET_BSD', this.filtered_doors)
    //   })
    //   .finally(() => {
    //     this.loading = false
    //   })
  },
  watch: {
    search() {
      if (this.search.length > 0) {
        this.filtered_doors.length > 0 ?
          this.filtered_doors = this.filtered_doors.filter(door => {
            return door.name.toLowerCase().includes(this.search.toLowerCase())
          })
          :
          this.filtered_doors = this.doors.filter(door => {
            return door.name.toLowerCase().includes(this.search.toLowerCase())
          })
      } else {
        this.filtered_doors = this.doors
      }
    },
    selected_brands() {
      this.currentFilter = 'brand'
      this.sortDoors()
    },
    selected_countries() {
      this.currentFilter = 'country'
      this.sortDoors()
    }
    // selected_brands() {
    //   if (this.selected_brands.length > 0) {
    //     this.filtered_doors = this.doors.filter(door => {
    //       return this.selected_brands.includes(parseInt(door.brand.id))
    //     })
    //     // this.filtered_countries = this.GET_COUNTRIES.filter(country => {
    //     //   return this.filtered_doors.map(door => {
    //     //     return door.country.id
    //     //   }).includes((country.id).toString())
    //     // })
    //   } else {
    //     this.filtered_doors = [...this.doors]
    //     // this.filtered_countries = [...this.GET_COUNTRIES]
    //   }
    //   // this.$store.commit('SET_BSD', this.filtered_doors)
    // },
    // selected_countries() {
    //   if (this.selected_countries.length > 0) {
    //     this.filtered_doors = this.GET_DOORS.filter(door =>
    //       this.selected_countries.includes(parseInt(door.country.id))
    //     )
    //   } else {
    //     this.filtered_doors = [...this.GET_DOORS]
    //   }
    //   this.$store.commit('SET_BSD', this.filtered_doors)
    // }
  },
  computed: {
    allDoors() {
      if (this.filtered_doors.length > 0) {
        return this.filtered_doors
      } else {
        return this.doors
      }
    },

    paginateDoors() {
      let chunk = 9
      let i = 0
      let c = 0
      let arrForChunk = []
      if (this.allDoors.length > chunk) {
        while (c !== Math.ceil(this.allDoors.length / chunk)) {
          arrForChunk.push(this.allDoors.slice(i, i += chunk))
          c++
        }
        return arrForChunk
      } else {
        arrForChunk[0] = this.allDoors
        return arrForChunk
      }

    },

    // ...mapGetters([
    //   'GET_DOORS',
    //   'GET_BRANDS',
    //   'GET_COUNTRIES',
    //   'GET_MATERIALS'
    // ])
    // doors() {
    //     if (this.search) {
    //         if(this.filtered_brands.length > 0) {
    //             return this.$store.getters.getDoors.filter(
    //                 door =>
    //                     door.name.toLowerCase().includes(this.search.toLowerCase()) && this.filtered_brands.includes(parseInt(door.brand_id))
    //             )
    //         } else {
    //             return this.$store.getters.getDoors.filter(
    //                 door => door.name.toLowerCase().includes(this.search.toLowerCase())
    //             )
    //         }
    //     } else {
    //         if(this.filtered_brands.length > 0) {
    //             return this.$store.getters.getDoors.filter(
    //                 door => this.filtered_brands.includes(parseInt(door.brand_id))
    //             )
    //         } else {
    //             return this.$store.getters.getDoors
    //         }
    //     }
    // },
    // brands() {
    //     return this.$store.getters.getBrands
    // }
  },
  methods: {
    async getDoors() {
      await axios.get('/api/doors')
        .then(res => {
          console.log('api/doors', res.data)
          this.doors = res.data.doors
          this.brands = res.data.brands
          this.countries = res.data.countries
          this.materials = res.data.materials
          this.colors = res.data.colors
        })
    },

    async addToCart(id) {
      await axios.post('/api/add_to_cart', {
        door_id: id,
        quantity: 1,
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

    sortDoors() {
      this.currentIndex = 1
      this.filtered_doors = []
      let vm = this
      this.doors.map(door => {
        if (vm.selected_brands.length > 0 ? vm.selected_brands.includes(door.brand.id) : '') {
          this.filtered_doors.indexOf(door) === -1 ? this.filtered_doors.push(door) : ''
        }
        if (vm.selected_countries.length > 0 ? vm.selected_countries.includes(door.country.id) : '') {
          this.filtered_doors.indexOf(door) === -1 ? this.filtered_doors.push(door) : ''
        }
      })
    }

    // ...mapActions([
    //   'GET_DOORS_API',
    //   'GET_BRANDS_API',
    //   'GET_COUNTRIES_API',
    //   'GET_MATERIALS_API'
    // ])
  },
  filters: {
    price_format(price) {
      return price.toLocaleString()
    }
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
  opacity: 0;
}
</style>
