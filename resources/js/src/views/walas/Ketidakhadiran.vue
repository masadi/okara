<template>
  <b-card no-body>
    <b-overlay :show="loadingForm" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <div v-if="isBusy" class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
        <div v-else>
          <b-form @submit="onSubmit">
            <template v-if="hasRole('waka') || hasRole('tu')">
              <formulir-waka @rombel="handleRombel" @hide_form="handleHideForm" :meta="meta" :form="form"></formulir-waka>
            </template>
            <template v-if="show_table">
              <b-table-simple bordered striped responsive>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center" width="30%">Nama Peserta Didik</b-th>
                    <b-th class="text-center" width="25%">NISN</b-th>
                    <b-th class="text-center" width="15%">Sakit</b-th>
                    <b-th class="text-center" width="15%">Izin</b-th>
                    <b-th class="text-center" width="15%">Tanpa Keterangan</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-for="(item, index) in data_siswa">
                    <b-tr>
                      <b-td>{{item.nama}}</b-td>
                      <b-td class="text-center">{{item.nisn}}</b-td>
                      <b-td class="text-center">
                        <template v-if="show_form">
                          <b-form-input v-model="form.sakit[item.anggota_rombel.anggota_rombel_id]" :disabled="disabled" />
                        </template>
                        <template v-else>
                          {{form.sakit[item.anggota_rombel.anggota_rombel_id]}}
                        </template>
                      </b-td>
                      <b-td class="text-center">
                        <template v-if="show_form">
                          <b-form-input v-model="form.izin[item.anggota_rombel.anggota_rombel_id]" :disabled="disabled" />
                        </template>
                        <template v-else>
                          {{form.izin[item.anggota_rombel.anggota_rombel_id]}}
                        </template>
                      </b-td>
                      <b-td class="text-center">
                        <template v-if="show_form">
                          <b-form-input v-model="form.alpa[item.anggota_rombel.anggota_rombel_id]" :disabled="disabled" />
                        </template>
                        <template v-else>
                          {{form.alpa[item.anggota_rombel.anggota_rombel_id]}}
                        </template>
                      </b-td>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
            </template>
            <b-form-group label-cols-md="3" v-if="show_form">
              <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
            </b-form-group>
          </b-form>
        </div>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BOverlay, BForm, BFormGroup, BFormInput, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol } from 'bootstrap-vue'
import FormulirWaka from './../components/FormulirWaka.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BOverlay,
    BForm, BFormGroup, BFormInput,
    BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol,
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
        aksi: 'ketidakhadiran',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        sakit: {},
        izin: {},
        alpa: {},
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
      this.$http.post('/walas/ketidakhadiran', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.show_table = getData.show
        this.show_form = getData.form
        this.data_siswa = getData.data_siswa
        var sakit = {}
        var izin = {}
        var alpa = {}
        this.data_siswa.forEach(function(siswa, key) {
          sakit[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi) ? siswa.anggota_rombel.absensi.sakit : null
          izin[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi) ? siswa.anggota_rombel.absensi.izin : null
          alpa[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi) ? siswa.anggota_rombel.absensi.alpa : null
        });
        this.form.sakit = sakit
        this.form.izin = izin
        this.form.alpa = alpa
      })
    },
    onSubmit(event) {
      event.preventDefault()
      this.loadingForm = true
      this.$http.post('/walas/save', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        })
      })
    },
    handleHideForm(){
      console.log('hide_form');
    },
    handleRombel(val){
      console.log(val);
      this.loadingForm = true
      this.$http.post('/waka/ketidakhadiran', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        this.show_table = true
        this.data_siswa = getData.data_siswa
        var sakit = {}
        var izin = {}
        var alpa = {}
        this.data_siswa.forEach(function(siswa, key) {
          sakit[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi) ? siswa.anggota_rombel.absensi.sakit : null
          izin[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi) ? siswa.anggota_rombel.absensi.izin : null
          alpa[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi) ? siswa.anggota_rombel.absensi.alpa : null
        });
        this.form.sakit = sakit
        this.form.izin = izin
        this.form.alpa = alpa
        console.log(getData);
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>