<template>
    <div>
        <b-form>
            <b-form-input v-model="fn"></b-form-input>
            <b-form-input v-model="ln"></b-form-input>
            <b-form-input v-model="email"></b-form-input>
            <b-form-input v-model="phone"></b-form-input>
        </b-form>
    </div>
</template>

<script>
export default {
    name: "Profile",
    data() {
        return {
            fn: '',
            ln: '',
            email: '',
            phone: ''
        }
    },
    mounted() {
        this.$store.dispatch('GET_USER_FROM_API')
            .then(() => {
                this.fn = this.$store.state.user.first_name
                this.ln = this.$store.state.user.last_name
                this.email = this.$store.state.user.email
                this.phone = this.$store.state.user.phone
            })
    }
}
</script>

<style scoped>

</style>
