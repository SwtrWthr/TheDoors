<template>
  <div class="mt-5">
    <v-snackbar
      v-model="sn"
      top
      right
      light
      text
      :color="snColor"
      :timeout="4500"
    >
      {{ snText }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="primary"
          text
          v-bind="attrs"
          @click="sn = false"
        >
          <v-icon light>{{ mdiClose }}</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
    <transition name="component-fade" mode="out-in">
      <component
        :is="phase"
        @change="(val) => (this.phase = val)"
        @snackbar="(text, status, color) => {
          this.snText = text
          this.sn = status
          this.snColor = color
        }"
      ></component>
    </transition>
  </div>
</template>

<script>
import {mdiClose} from '@mdi/js'
import VueLogin from "../components/VueLogin";
import VueRegistration from "../components/VueRegistration";

export default {
  name: "Login",
  components: {
    VueLogin,
    VueRegistration
  },
  data() {
    return {
      mdiClose,
      phase: 'VueLogin',
      snText: '',
      sn: false,
      snColor: 'success'
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
