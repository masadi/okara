<template>
  <div>
    <p class="clearfix mb-0">
      <span class="float-md-left d-block d-md-inline-block mt-25">
        Hak Cipta &copy {{ new Date().getFullYear() }}
        <b-link class="ml-25" :href="copyright_url" target="_blank">{{copyright_text}}</b-link>
      </span>
      <span class="float-md-right d-none d-md-block">Versi {{version}}
        <feather-icon
          icon="HeartIcon"
          size="21"
          class="text-danger stroke-current"
        />
      </span>
    </p>
    <p class="d-block d-md-none small">Untuk tampilan terbaik minimal resolusi 1360 x 768</p>
  </div>
</template>

<script>
import { BLink } from 'bootstrap-vue'
import { $themeConfig } from '@themeConfig'
export default {
  components: {
    BLink,
  },
  data() {
    return {
      version: '',
      copyright_text: '',
      copyright_url: '',
    }
  },
  created() {
    this.version = app_version
    this.copyright_text = app_copyright_text
    this.copyright_url = app_copyright_url
  },
}
</script>
