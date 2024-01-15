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
                  <b-th class="text-center">Nama Eskul</b-th>
                  <b-th class="text-center">Pembina</b-th>
                  <b-th class="text-center">Predikat</b-th>
                  <b-th class="text-center">Deskripsi</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="(item, index) in data_siswa">
                  <b-tr>
                    <b-td :rowspan="item.anggota_rombel.anggota_ekskul.length + 1">{{item.nama}}</b-td>
                  </b-tr>
                  <template v-for="(anggota_ekskul, key) in item.anggota_rombel.anggota_ekskul">
                    <b-tr>
                      <b-td>{{anggota_ekskul.rombongan_belajar.kelas_ekskul.nama_ekskul}}</b-td>
                      <b-td>{{anggota_ekskul.rombongan_belajar.kelas_ekskul.guru.nama_lengkap}}</b-td>
                      <b-td>{{(anggota_ekskul.single_nilai_ekstrakurikuler) ? nilai_ekskul(anggota_ekskul.single_nilai_ekstrakurikuler.nilai) : '-'}}</b-td>
                      <b-td>{{(anggota_ekskul.single_nilai_ekstrakurikuler) ? anggota_ekskul.single_nilai_ekstrakurikuler.deskripsi_ekskul : '-'}}</b-td>
                    </b-tr>
                  </template>
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
import { BCard, BCardBody, BSpinner, BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol } from 'bootstrap-vue'
import FormulirWaka from './../components/FormulirWaka.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BOverlay,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol,
    FormulirWaka
  },
  data() {
    return {
      disabled: false,
      show_table: false,
      show_form: false,
      loadingForm: false,
      isBusy: true,
      form: {
        aksi: 'nilai-ekskul',
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
      this.$http.post('/walas/nilai-ekskul', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.show_table = getData.show
        this.show_form = getData.form
        this.data_siswa = getData.data_siswa
      })
    },
    nilai_ekskul(nilai){
      var predikat = {
        1: 'Sangat Baik',
        2: 'Baik',
        3: 'Cukup',
        4: 'Kurang',
      }
      return predikat[nilai];
    },
    handleHideForm(){
      console.log('hide_form');
      this.show_table = false
      this.data_siswa = []
    },
    handleRombel(val){
      console.log(val);
      this.loadingForm = true
      this.$http.post('/waka/nilai-ekskul', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        this.show_table = getData.show
        this.data_siswa = getData.data_siswa
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>