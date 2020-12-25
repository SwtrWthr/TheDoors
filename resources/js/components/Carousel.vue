<template>
  <b-container
    class="px-0"
    fluid="md"
  >
    <v-carousel
      v-model="model"
      hide-delimiter-background
      height="400"
      light
      cycle
      interval="5000"
      show-arrows-on-hover
    >
      <v-carousel-item
        v-for="(door, i) in doors.slice(0,3)"
        :key="door.id"
      >
        <v-sheet
          color="white"
          class="text-dark"
          height="100%"
          tile
        >
          <v-row
            class="fill-height"
          >
            <v-col
              class="fill-height"
              cols="6"
              style="z-index: 1"
            >
              <v-row
                class="fill-height"
                align="center"
                justify="center"
              >
                <div class="text-md-h2">
                  <p class="text-info h5 mb-3">НОВИНКА!</p>
                  <p class="text-sm-h3 text-sm-end mb-0">{{ door.name }}</p>
                  <v-btn
                    elevation="7"
                    color="primary"
                    :ripple="false"
                    class="text-capitalize ml-auto"
                    :to="{
                      name: 'DoorView',
                      params: {
                        id: door.id
                      }
                    }"
                  >Подробнее</v-btn>
                </div>
              </v-row>
            </v-col>
            <v-col
              cols="6"
              class="fill-height"
            >
              <v-row
                class="fill-height"
                align="center"
                justify="center"
              >
<!--                <v-col-->
<!--                 md="12"-->
<!--                 class="fill-height"-->
<!--                >-->
                  <b-img :src="'../images/'+door.image" class="h-100"></b-img>
<!--                </v-col>-->
              </v-row>
            </v-col>
          </v-row>
        </v-sheet>
      </v-carousel-item>
    </v-carousel>
  </b-container>
</template>

<script>
export default {
  name: "Carousel",
  props: ['doors'],
  data() {
    return {
      model: 0
    }
  },
}
</script>

<style scoped>

</style>
