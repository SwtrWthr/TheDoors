<template>
  <v-footer
    light
    padless
    class="w-100 mt-10"
  >
    <v-card
      flat
      width="100%"
      tile
      class="bg-dark lighten-1 text-dark"
    >
      <v-card-text class="text-center">
        <v-btn
          v-for="icon in icons"
          :key="icon"
          class="mx-4 white--text"
          icon
        >
          <v-icon size="24px">
            {{ icon }}
          </v-icon>
        </v-btn>
      </v-card-text>

      <v-card-text class="white--text pt-0">
        <v-row>
          <b-col
            cols="6"
          >
            <v-row
              align="center"
              justify="center"
              class="fill-height"
            >
                <v-list
                 color="transparent"
                >
                  <v-list-item
                    v-for="(item, i) in items"
                    :key="i"
                  >
                    <v-list-item-avatar color="gray">
                        <v-icon dark size="24">{{ item.icon }}</v-icon>
                    </v-list-item-avatar>

                    <v-list-item-content>
                      <v-list-item-title class="text-white">{{ item.text }}</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
<!--              </div>-->
            </v-row>
          </b-col>
          <b-col
            cols="6"
          >
            <v-row
              align="center"
              justify="center"
            >
              <div id="map" style="width:300px; height:300px"></div>
            </v-row>
          </b-col>
        </v-row>
      </v-card-text>

      <v-divider
        class="my-0"
        dark
      ></v-divider>

      <v-card-text class="white--text text-center">
        {{ new Date().getFullYear() }} — <strong>Doors</strong>
      </v-card-text>
    </v-card>
  </v-footer>
</template>

<script>
import {mdiFacebook, mdiTwitter, mdiInstagram, mdiVk, mdiMapMarker, mdiPhone, mdiEmail} from '@mdi/js'

export default {
  name: "Footer",
  data() {
    return {
      items: [
        {
          icon: mdiMapMarker,
          text: 'г. Алматы, ул. Каблукова, д. 117А'
        },
        {
          icon: mdiPhone,
          text: '+7(727) 376-55-86'
        },
        {
          icon: mdiEmail,
          text: 'heaven@psycho.kz'
        }
      ],
      icons: [
        mdiFacebook,
        mdiInstagram,
        mdiTwitter,
        mdiVk
      ]
    }
  }
}
</script>

<style scoped>

</style>
