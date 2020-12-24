import Vue from 'vue';
import Vuex from 'vuex';
import router from "../router";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    blet: 4,
    user: null,
    doors: [],
    brands: [],
    countries: [],
    materials: [],
    colors: [],
    before_search_doors: [],
    log_reg: true,
    token: localStorage.getItem('token') || '',
    loginStatus: null,
    cart: []
  },
  getters: {
    GET_USER: state => state.user,
    GET_CART: state => state.cart,
    GET_BLET: state => state.blet,
    GET_TOKEN: state => state.token,
    GET_LOGIN_STATUS: state => state.loginStatus,
    GET_DOORS: state => state.doors,
    GET_BRANDS: state => state.brands,
    GET_LOG_REG: state => state.log_reg,
    GET_COUNTRIES: state => state.countries,
    GET_MATERIALS: state => state.materials,
    GET_COLORS: state => state.colors,
    GET_BEFORE_SEARCH_DOORS: state => state.before_search_doors
  },
  mutations: {
    SET_USER(state, data) {
      return state.user = data
    },
    SET_CART(state, data) {
      return state.cart = data
    },
    SET_BLET(state) {
      return state.blet += 1
    },
    SET_DOORS(state, data) {
      return state.doors = data
    },
    SET_BRANDS(state, data) {
      return state.brands = data
    },
    SET_COUNTRIES(state, data) {
      return state.countries = data
    },
    SET_MATERIALS(state, data) {
      return state.materials = data
    },
    SET_COLORS(state, data) {
      return state.colors = data
    },
    SET_BSD(state, data) {
      return state.before_search_doors = data
    },
    SET_TOKEN(state, data) {
      return state.token = data
    },
    SET_LOGIN_STATUS(state, data) {
      return state.loginStatus = data
    }
  },
  actions: {
    async GET_DOORS_API({commit}) {
      await axios.get('/api/doors')
        .then(res => {
          console.log(res.data)
          commit('SET_DOORS', res.data.doors)
        })
    },
    async GET_USER_FROM_API({commit}) {
      try {
        await axios.post('/api/auth/me',{}, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        }).then(res => {
          console.log(res)
          commit('SET_USER', res.data.user)
          commit('SET_CART', res.data.cart)
        })
      } catch (e) {
        console.log('blet' ,e)
      }
    },
    async LOGOUT_USER({commit}) {
      await axios.post('/api/auth/logout', {}, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
        .then(res => {
          commit('SET_TOKEN', null)
          commit('SET_USER', null)
          commit('SET_LOGIN_STATUS', null)
          commit('SET_CART', [])
          localStorage.removeItem('token')
          router.push('/')
        })
    },
    async GET_BRANDS_API({commit}) {
      await axios.get('/api/doors')
        .then(res => {
          commit('SET_BRANDS', res.data.brands)
        })
    },
    async GET_COUNTRIES_API({commit}) {
      await axios.get('/api/doors')
        .then(res => {
          commit('SET_COUNTRIES', res.data.countries)
        })
    },
    GET_MATERIALS_API({commit}) {
      axios.get('/api/doors')
        .then(res => {
          commit('SET_MATERIALS', res.data.materials)
        })
    },
    GET_COLORS_API({commit}) {
      axios.get('/api/doors')
        .then(res => {
          commit('SET_COLORS', res.data.colors)
        })
    }
  }

})
