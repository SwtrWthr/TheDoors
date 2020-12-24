<template>
  <div>
    <div class="bg-dark">
      <b-container fluid="xl">
        <b-navbar type="dark" variant="dark" class="p-0">
          <b-collapse is-nav>
            <b-navbar-nav small>
              <b-nav-item>
                <b-icon icon="telephone-fill" font-scale="1"></b-icon>
                +7 (707) 777-77-77
              </b-nav-item>
              <b-nav-item class="d-none d-sm-block">
                <b-icon icon="envelope-fill" font-scale="1"></b-icon>
                doors@mail.ru
              </b-nav-item>
            </b-navbar-nav>
            <b-navbar-nav class="ml-auto" small>
              <b-nav-item v-if="user">
                <b-icon icon="person-fill" font-scale="1"></b-icon>
                {{ user.first_name }} {{ user.last_name }}({{ user.role_id == 2 ? 'User' : 'Admin' }})
              </b-nav-item>
              <b-nav-item v-if="user" href="#" @click.prevent="logout()">
                Выйти
                <b-icon icon="power" font-scale="1"></b-icon>
              </b-nav-item>
              <b-nav-item v-else to="/login">
                <b-icon icon="outlet" font-scale="1"></b-icon>
                Войти
              </b-nav-item>
            </b-navbar-nav>
          </b-collapse>
        </b-navbar>
      </b-container>
    </div>
    <div class="bg-light">
      <b-container fluid="xl" class="px-0">
        <b-navbar toggleable="sm" type="light" variant="light">
          <b-navbar-nav>
            <b-navbar-brand to="/" class="font-weight-bold" tag="h1" style="font-size: 26px">TheDoors
            </b-navbar-brand>
          </b-navbar-nav>
          <b-navbar-toggle target="nav-collapse">
            <template #default="{ expanded }">
              <b-icon v-if="expanded" icon="chevron-up"></b-icon>
              <b-icon v-else icon="chevron-down"></b-icon>
            </template>
          </b-navbar-toggle>
          <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav class="ml-auto text-center font-weight-bolder">
              <b-nav-item href="#">
                <span style="font-size: 16px">Контакты</span>
                <v-icon large>{{mdiCardBulleted}}</v-icon>
              </b-nav-item>

              <b-nav-item href="#" to="/cart" active-class="active">
                <span style="font-size: 16px">Корзина</span>
                <v-badge
                  color="primary"
                  :value="cart.length"
                  :content="cart.length"
                  overlap
                >
                  <v-icon large>{{mdiCartOutline}}</v-icon>
                </v-badge>
              </b-nav-item>
              <b-nav-item v-if="user && user.role_id == 1" to="/adminka" active-class="active">
                Adminskaya
                <v-icon large>{{mdiAlienOutline}}</v-icon>
              </b-nav-item>
            </b-navbar-nav>
          </b-collapse>
        </b-navbar>
      </b-container>
    </div>
    <b-container fluid="xl">
      <hr class="m-0">
    </b-container>
  </div>
</template>

<script>
import {mdiCartOutline, mdiCardBulleted, mdiAlienOutline} from '@mdi/js'
export default {
  data() {
    return {
      mdiAlienOutline,
      mdiCartOutline,
      mdiCardBulleted
    }
  },
  created() {
    this.get_auth()
    if (localStorage.getItem('token')) {
      this.$store.dispatch('GET_USER_FROM_API')
    }
  },
  computed: {
    user() {
      return this.$store.getters.GET_USER
    },
    cart() {
      return this.$store.getters.GET_CART
    }
  },
  methods: {
    logout() {
      this.$store.dispatch('LOGOUT_USER')
    },
    async get_auth() {
      await axios.post('/api/auth/me')
        .then(res => {
          console.log(res.data)
        })
      // console.log(res.data)
    }
  }
}
</script>
