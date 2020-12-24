<template>
  <b-row class="d-flex justify-content-center">
    <b-col lg="4" class="p-4 bg-white shadow rounded">
      <p class="h1 font-weight-bold m-2 mb-4 text-center">Registration</p>
      <v-form v-model="regValid" @submit.prevent="registration" class="mt-3">
        <label class="text-body-2 text-secondary mb-0">e-mail</label>
        <v-text-field
          color="primary"
          :append-icon="mdiEmail"
          v-model="user.email"
          :rules="emailRules"
          :error-messages="errors"
          @input="errors=[]"
          outlined
          required
        ></v-text-field>

        <label class="text-body-2 text-secondary mb-0">first name</label>
        <v-text-field
          color="primary"
          :append-icon="mdiAccountEdit"
          v-model="user.first_name"
          :rules="fNameRules"
          outlined
          required
        ></v-text-field>

        <label class="text-body-2 text-secondary mb-0">last name</label>
        <v-text-field
          color="primary"
          :append-icon="mdiAccountEdit"
          v-model="user.last_name"
          :rules="lNameRules"
          outlined
          required
        ></v-text-field>

        <label class="text-body-2 text-secondary mb-0">phone</label>
        <v-text-field
          color="primary"
          :append-icon="mdiPhone"
          v-model="user.phone"
          v-mask="'+ 7 (###) ###-##-##'"
          outlined
          required
          @focus="user.phone = user.phone || '+ 7 ('"
        ></v-text-field>

        <label class="text-body-2 text-secondary mb-0">password</label>
        <v-text-field
          :append-icon="show_pass ? mdiEye : mdiEyeOff"
          @click:append="show_pass = !show_pass"
          :type="show_pass ? 'type' : 'password'"
          v-model="user.password"
          :rules="passwordRules"
          outlined
          required
        >
        </v-text-field>

        <label class="text-body-2 text-secondary mb-0">repeat password</label>
        <v-text-field
          :append-icon="show_pass_rep ? mdiEye : mdiEyeOff"
          @click:append="show_pass_rep = !show_pass_rep"
          :type="show_pass_rep ? 'type' : 'password'"
          v-model="rep_pass"
          :rules="passwordRules"
          outlined
          required
        >
        </v-text-field>
        <v-btn
          :ripple="false"
          type="submit"
          :disabled="!regValid"
          block
          depressed
          large
          color="primary"
        >войти
        </v-btn>
        <p class="text-center mt-8">
          <span>Уже есть аккаунт? </span>
          <a @click="$emit('change', 'VueLogin')" style="cursor: pointer; color: #0BC4B8">Войдите</a>
        </p>
      </v-form>
    </b-col>
  </b-row>
</template>

<script>
import {mdiAccountEdit, mdiEmail, mdiEye, mdiEyeOff, mdiPhone} from '@mdi/js'

export default {
  name: "VueRegistration",
  data() {
    return {
      snackbar: false,
      snackbarText: '',
      snackbarColor: 'success',
      regValid: false,
      mdiEmail,
      mdiAccountEdit,
      mdiEye,
      mdiEyeOff,
      mdiPhone,
      user: {
        email: '',
        first_name: '',
        last_name: '',
        phone: '',
        password: '',
      },
      show_pass: false,
      show_pass_rep: false,
      rep_pass: '',
      errors: [],
      passwordRules: [(v) => !!v || "Введите пароль"],
      emailRules: [(v) => /.+@.+\..+/.test(v) || "Введите э-мейл"],
      fNameRules: [(v) => !!v || 'Введите имя'],
      lNameRules: [(v) => !!v || 'Введите фамилию']
    }
  },
  methods: {
    registration() {
      this.user.phone = this.user.phone.replace(/[^0-9.]/g, "")
      axios.post('/api/auth/register', this.user)
        .then(res => {
          console.log(res.data)
          if (res.data.status === true) {
            this.$emit('snackbar', 'Успешная регистрация!', true, 'success')
            setTimeout(() => {this.$emit('change', 'VueLogin')}, 450)
          } else {
            this.$emit('snackbar', 'Ошибка!', true, 'error')
            this.errors.push(res.data.error)
          }
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
