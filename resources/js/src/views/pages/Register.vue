<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">

      <!-- Brand logo-->
      <b-link class="brand-logo">
        <vuexy-logo />

        <h2 class="brand-text text-primary ml-1">
          {{app.name}}
        </h2>
      </b-link>
      <!-- /Brand logo-->

      <!-- Left Text-->
      <b-col lg="8" class="d-none d-lg-flex align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img fluid :src="imgUrl" alt="Register V2" />
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Register-->
      <b-col lg="4" class="d-flex align-items-center auth-bg px-2 p-lg-5">
        <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
          <div class="text-center">
            <b-card-title class="mb-1 font-weight-bold" title-tag="h1">
              <b-img :src="logoUrl" style="height:28px" /> {{app.name}}
            </b-card-title>
            <b-card-sub-title sub-title-text-variant="black">
              Versi {{app.version}}
            </b-card-sub-title>
            <b-card-text class="my-1">
              Silahkan register untuk dapat mengakses Aplikasi
            </b-card-text>
          </div>
          <b-alert show variant="danger" v-show="message">
            <div class="alert-body">{{message}}</div>
          </b-alert>
          <!-- form -->
          <validation-observer ref="registerForm" #default="{invalid}">
            <b-form class="auth-register-form mt-2" @submit.prevent="register">
              <!-- username -->
              <b-form-group label="NPSN" label-for="register-username">
                <validation-provider #default="{ errors }" name="Username" vid="username" rules="required">
                  <b-form-input id="register-username" v-model="username" name="register-username" :state="errors.length > 0 ? false:null" placeholder="NPSN" />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- email -->
              <b-form-group label="Email" label-for="register-email">
                <validation-provider #default="{ errors }" name="Email" vid="email" rules="required|email">
                  <b-form-input id="register-email" v-model="userEmail" name="register-email" :state="errors.length > 0 ? false:null" placeholder="Email Dapodik" />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- password -->
              <b-form-group label-for="register-password" label="Password">
                <validation-provider #default="{ errors }" name="Password" vid="password" rules="required">
                  <b-input-group class="input-group-merge" :class="errors.length > 0 ? 'is-invalid':null">
                    <b-form-input id="register-password" v-model="password" class="form-control-merge" :type="passwordFieldType" :state="errors.length > 0 ? false:null" name="register-password" placeholder="············" />
                    <b-input-group-append is-text>
                      <feather-icon :icon="passwordToggleIcon" class="cursor-pointer" @click="togglePasswordVisibility" />
                    </b-input-group-append>
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <b-button variant="primary" block type="submit" :disabled="invalid">
                Register
              </b-button>
            </b-form>
          </validation-observer>

          <p class="text-center mt-2">
            <span>Sudah terdaftar?</span>
            <b-link :to="{name:'auth-login'}">
              <span>&nbsp;Login Disini</span>
            </b-link>
          </p>
        </b-col>
      </b-col>
    <!-- /Register-->
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require */
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import {
  BRow,
  BCol,
  BLink,
  BButton,
  BForm,
  BFormCheckbox,
  BFormGroup,
  BFormInput,
  BInputGroup,
  BInputGroupAppend,
  BImg,
  BCardTitle,
  BCardSubTitle,
  BCardText,
  BAlert,
} from 'bootstrap-vue'
import { required, email } from '@validations'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import store from '@/store/index'
import useJwt from '@/auth/jwt/useJwt'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
export default {
  components: {
    VuexyLogo,
    BRow,
    BImg,
    BCol,
    BLink,
    BButton,
    BForm,
    BCardText,
    BCardTitle,
    BCardSubTitle,
    BFormCheckbox,
    BFormGroup,
    BFormInput,
    BInputGroup,
    BInputGroupAppend,
    // validations
    ValidationProvider,
    ValidationObserver,
    BAlert,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      status: '',
      username: '',
      userEmail: '',
      password: '',
      message: '',
      //sideImg: '/images/pages/register-v2.svg'),
      // validation
      sideImg: '/images/pages/bg_login.png',
      logoImg: '/images/logo/logo.png',
      required,
      email,
      //app: store.state.appConfig.app,
      app: {
        name: '',
        version: '',
      }
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    imgUrl() {
      if (store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.sideImg = '/images/pages/register-v2-dark.svg'
        return this.sideImg
      }
      return this.sideImg
    },
    logoUrl(){
      return this.logoImg
    }
  },
  created() {
    this.app.name = app_name
    this.app.version = app_version
    /*if(!app_registration){
      this.$router.replace({name: 'misc-not-authorized'})
    }*/
    this.$http.get('/sekolah', {
    }).then(response => {
      let getData = response.data
      if(getData.data >= 10){
        console.log('redirect');
        this.$router.replace({name: 'misc-not-authorized'})
      }
      console.log(response.data);
    }).catch(error => {
      console.log(error);
    })
  },
  methods: {
    register() {
      this.$refs.registerForm.validate().then(success => {
        if (success) {
          this.$http.post('/auth/register', {
            username: this.username,
            email: this.userEmail,
            password: this.password,
            //c_password: this.password
          }).then(response => {
            let getData = response.data
            console.log(getData);
            if(getData.errors){
              this.$refs.registerForm.setErrors(getData.errors)
            } else if(getData.error){
              this.message = getData.message
            } else {
              this.$router.replace({name: 'auth-login'}).then(() => {
                this.$toast({
                  component: ToastificationContent,
                  position: 'bottom-center',
                  props: {
                    title: `Registrasi Berhasil`,
                    icon: 'CoffeeIcon',
                    variant: 'success',
                    text: `Anda telah berhasil register. Sekarang Anda dapat mulai berselancar di Aplikasi e-Rapor SMK!`,
                  },
                })
              })
            }
          }).catch(error => {
            this.$refs.registerForm.setErrors(error.response.data.error)
          })
        }
      })
    },
  },
}
/* eslint-disable global-require */
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-auth.scss';
</style>
