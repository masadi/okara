<template>
  <b-card no-body>
    <b-overlay :show="loadingForm" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <div v-if="isBusy" class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
        <div v-else>
          <template v-if="hasRole('waka') || hasRole('tu')">
            <formulir-waka @rombel="handleRombel" @hide_form="handleHideForm" :meta="meta" :form="form"></formulir-waka>
          </template>
          <template v-if="show_table">
            <b-table-simple bordered striped responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center">Nama Peserta Didik</b-th>
                  <b-th class="text-center">NISN</b-th>
                  <b-th class="text-center">Halaman Depan</b-th>
                  <b-th class="text-center">Rapor Akademik</b-th>
                  <b-th class="text-center" v-if="merdeka">Rapor P5</b-th>
                  <b-th class="text-center">Dokumen Pendukung</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="(item, index) in data_siswa">
                  <b-tr>
                    <b-td>{{item.nama}}</b-td>
                    <b-td class="text-center">{{item.nisn}}</b-td>
                    <b-td class="text-center">
                      <b-button variant="success" :href="`/cetak/rapor-cover/${item.anggota_rombel.anggota_rombel_id}`" target="_blank"><font-awesome-icon icon="fa-solid fa-file" size="2xl" /></b-button>
                    </b-td>
                    <b-td class="text-center">
                      <b-button variant="warning" :href="`/cetak/rapor-nilai-akhir/${item.anggota_rombel.anggota_rombel_id}/${form.sekolah_id}/${form.semester_id}`" target="_blank"><font-awesome-icon icon="fa-solid fa-file-pdf" size="2xl" /></b-button>
                    </b-td>
                    <b-td class="text-center" v-if="merdeka">
                      <b-button variant="info" :href="`/cetak/rapor-p5/${item.anggota_rombel.anggota_rombel_id}/${form.semester_id}`" target="_blank"><font-awesome-icon icon="fa-solid fa-file-pdf" size="2xl" /></b-button>
                    </b-td>
                    <b-td class="text-center">
                      <b-button variant="danger" :href="`/cetak/rapor-pelengkap/${item.anggota_rombel.anggota_rombel_id}/${item.anggota_rombel.rombongan_belajar_id}`" target="_blank"><font-awesome-icon icon="fa-solid fa-file-pdf" size="2xl" /></b-button>
                    </b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
          </template>
        </div>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton } from 'bootstrap-vue'
import FormulirWaka from './../components/FormulirWaka.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BOverlay,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton,
    FormulirWaka
  },
  data() {
    return {
      disabled: false,
      show_table: false,
      show_form: false,
      loadingForm: false,
      isBusy: true,
      merdeka: false,
      form: {
        aksi: 'cetak-rapor',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        tingkat: '',
        rombongan_belajar_id: '',
      },
      meta: {
        tingkat_feedback: '',
        tingkat_state: null,
        rombongan_belajar_id_feedback: '',
        rombongan_belajar_id_state: '',
      },
      data_siswa: [],
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.$http.post('/walas/cetak-rapor', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.show_table = getData.show
        this.show_form = getData.form
        this.data_siswa = getData.data_siswa
        this.merdeka = getData.merdeka
      })
    },
    handleHideForm(){
      console.log('hide_form');
      this.show_table = false
      this.data_siswa = []
    },
    handleRombel(val){
      console.log(val);
      this.loadingForm = true
      this.$http.post('/waka/cetak-rapor', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        this.show_table = getData.show
        this.data_siswa = getData.data_siswa
        this.merdeka = getData.merdeka
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>