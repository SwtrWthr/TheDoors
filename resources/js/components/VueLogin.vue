<template>
  <b-row class="d-flex justify-content-center">
    <b-col lg="4" class="p-4 bg-white shadow rounded">
      <p class="h1 font-weight-bold m-2 mb-4 text-center">Login</p>
      <v-form v-model="loginValid" @submit.prevent="login()" class="mt-3">
        <label class="text-body-2 text-secondary mb-0">e-mail</label>
        <v-text-field
          color="primary"
          :append-icon="mdiEmail"
          v-model="user.email"
          :rules="emailRules"
          :error-messages="errors"
          @input="errors=[]"
          required
        ></v-text-field>

        <label class="text-body-2 text-secondary mb-0">password</label>
        <v-text-field
          :append-icon="show_pass ? mdiEye : mdiEyeOff"
          @click:append="show_pass = !show_pass"
          :type="show_pass ? 'type' : 'password'"
          v-model="user.password"
          :rules="passwordRules"
          required
        >
        </v-text-field>
        <v-btn
          :ripple="false"
          type="submit"
          :disabled="!loginValid"
          block
          depressed
          large
          color="primary"
        >войти
        </v-btn>
        <p class="text-center mt-8">
          <span>Нет аккаунта? </span>
          <a @click="$emit('change', 'VueRegistration')" style="cursor: pointer; color: #0BC4B8">Зарегистрируйтесь</a>
        </p>
      </v-form>
    </b-col>
  </b-row>
</template>

<script>
import {mdiEmail, mdiEye, mdiEyeOff} from '@mdi/js'
import router from "../router";

export default {
  name: "VueLogin",
  data() {
    return {
      loginValid: true,
      mdiEye,
      mdiEyeOff,
      mdiEmail,
      user: {
        email: '',
        password: ''
      },
      errors: [],
      show_pass: false,
      passwordRules: [(v) => !!v || "Введите пароль"],
      emailRules: [(v) => /.+@.+\..+/.test(v) || "Введите э-мейл"],
    }
  },
  methods: {
    login() {
      axios.post('/api/auth/login', this.user)
        .then(res => {
          localStorage.setItem('token', res.data.access_token)
          this.$store.commit('SET_TOKEN', res.data.access_token)
          this.$store.dispatch('GET_USER_FROM_API')
          setTimeout(() => {
            router.push('/')
          }, 200)
        })
        .catch(err => {
          this.errors.push('Неправильный логин или пароль!')
          this.user.password = ''
        })
    }
  }
}
</script>

<style scoped>
.component-fade-enter-active, .component-fade-leave-active {
  transition: opacity .3s ease;
}

.component-fade-enter, .component-fade-leave-to
  /* .component-fade-leave-active below version 2.1.8 */
{
  opacity: 0;
}
</style>
