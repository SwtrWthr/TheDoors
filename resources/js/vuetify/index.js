import Vue from 'vue'
import Vuetify from "vuetify"
import 'vuetify/dist/vuetify.min.css'
Vue.use(Vuetify)

const options = {
    theme: {
        themes: {
            light: {
                primary: '#17a2b8',
                secondary: '#b0bec5',
                success: '#0BC3B8',
                accent: '#8c9eff',
                error: '#F16635',
                gray: '#5A6275',
                live: '#F16635',
                grayLight: '#9FA4B1',
            },
            dark: {
                primary: '#0BC4B8',
                secondary: '#66F0E3',
                live: '#F16635',
                gray: '#5A6275',
                grayLight: '#9FA4B1'
            }
        },
    },
    icons: {
        iconfont: 'mdiSvg',
    },
}

export default new Vuetify(options)
