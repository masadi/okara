<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <b-form @submit="onSubmit">
          <formulir ref="formulir" :meta="meta" :form="form" @show_form="handleShowForm" @hide_form="handleHideForm"></formulir>
          <b-row v-if="show">
            <template v-if="data_tp.length">
              <b-col cols="12" class="mb-2">
                <b-row>
                  <b-col cols="6">
                    <b-form-group :invalid-feedback="template_excel_feedback" :state="template_excel_state">
                      <b-form-file v-model="template_excel" :state="template_excel_state" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
                    </b-form-group>
                  </b-col>
                  <b-col cols="6">
                    <b-button block variant="primary" :href="link_template_tp" target="_blank">Unduh Template Nilai Akhir</b-button>
                  </b-col>
                </b-row>
              </b-col>
              <b-col cols="12">
                <b-table-simple bordered striped responsive>
                  <b-thead>
                    <b-tr>
                      <b-th class="text-center">No</b-th>
                      <b-th class="text-center">Nama Peserta Didik</b-th>
                      <b-th class="text-center">Nilai Akhir</b-th>
                      <b-th class="text-center">Ketercapaian Kompetensi</b-th>
                      <b-th class="text-center">Deskripsi Tujuan Pembelajaran</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <template v-for="(siswa, index) in data_siswa">
                      <b-tr>
                        <b-td class="text-center" style="vertical-align:top" :rowspan="data_tp.length + 1">{{index + 1}}</b-td>
                        <b-td :rowspan="data_tp.length + 1" style="vertical-align:top">{{siswa.nama}}</b-td>
                        <b-td :rowspan="data_tp.length + 1" style="vertical-align:top">
                          <b-form-input v-model="form.nilai[siswa.anggota_rombel.anggota_rombel_id]" />
                        </b-td>
                      </b-tr>
                      <template v-for="(tp, i) in data_tp">
                        <b-tr>
                          <b-td>
                            <!--[siswa.anggota_rombel.anggota_rombel_id][tp.tp_id]-->
                            <v-select v-model="form.kompeten[siswa.anggota_rombel.anggota_rombel_id+'#'+tp.tp_id]" :reduce="nama => nama.id" label="nama" :options="data_capaian" placeholder="== Pilih Capaian ==" :searchable="false"></v-select>
                          </b-td>
                          <b-td>
                            {{tp.deskripsi}}
                          </b-td>
                        </b-tr>
                      </template>
                    </template>
                  </b-tbody>
                </b-table-simple>
              </b-col>
              <b-col cols="12">
                <b-form-group label-cols-md="3">
                  <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
                </b-form-group>
              </b-col>
            </template>
            <template v-else>
              <b-col cols="12">
                <b-alert show variant="danger">
                  <div class="alert-body text-center">
                    <h2>Tidak ditemukan data Tujuan Pembelajaran</h2>
                    <p>Silahkan tambah data Tujuan Pembelajaran terlebih dahulu <b-link to="/referensi/tujuan-pembelajaran">disini</b-link></p>
                  </div>
                </b-alert>
              </b-col>
            </template>
          </b-row>
        </b-form>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BOverlay, BCardBody, BForm, BFormGroup, BFormInput, BFormFile, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BAlert, BLink } from 'bootstrap-vue'
import Formulir from './../components/Formulir.vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BOverlay,
    BCardBody,
    BForm,
    BFormGroup,
    BFormInput,
    BFormFile,
    BRow,
    BCol,
    BButton,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    BAlert,
    BLink,
    Formulir,
    vSelect
  },
  data() {
    return {
      show: false,
      isBusy: false,
      form: {
        tahun_pelajaran: '',
        tingkat: '',
        jenis_rombel: '',
        rombongan_belajar_id: '',
        mata_pelajaran_id: '',
        pembelajaran_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        merdeka: false,
        nilai: {},
        kompeten: {},
      },
      meta: {
        tingkat_feedback: '',
        tingkat_state: null,
        jenis_rombel_feedback: '',
        jenis_rombel_state: null,
        rombongan_belajar_id_state : null,
        pembelajaran_id_state : null,
        rombongan_belajar_id_feedback: '',
        pembelajaran_id_feedback: '',
      },
      data_siswa: [],
      data_tp: [],
      template_excel: null,
      template_excel_feedback: '',
      template_excel_state: null,
      link_template_tp: '',
      data_capaian: [
        {
          id: '0',
          nama: 'Tidak tercapai',
        },
        {
          id: '1',
          nama: 'Tercapai',
        }
      ],
    }
  },
  created() {
    //this.tahun_pelajaran = this.user.semester.nama
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.tahun_pelajaran = this.user.semester.nama
    /*this.$http.get('/dashboard').then(response => {
      this.isBusy = false
      this.data = response.data
    })*/
  },
  methods: {
    onFileChange(e) {
      this.isBusy = true
      this.template_excel = e.target.files[0];
      const data = new FormData();
      data.append('template_excel', (this.template_excel) ? this.template_excel : '');
      data.append('rombongan_belajar_id', this.form.rombongan_belajar_id)
      data.append('pembelajaran_id', this.form.pembelajaran_id)
      data.append('sekolah_id', this.form.sekolah_id)
      data.append('merdeka', this.form.merdeka)
      this.$http.post('/penilaian/upload-nilai', data).then(response => {
        this.isBusy = false
        let data = response.data
        if(data.errors){
          this.template_excel_state = (data.errors.template_excel) ? false : null
          this.template_excel_feedback = (data.errors.template_excel) ? data.errors.template_excel.join(', ') : ''
        } else {
          this.$swal({
            icon: data.icon,
            title: data.title,
            html: data.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.handleHideForm()
            this.onReset()
          })
        }
      });
    },
    handleShowForm(){
      this.link_template_tp = '/downloads/template-nilai-akhir/'+this.form.pembelajaran_id
      this.isBusy = true
      this.$http.post('/penilaian/get-nilai-akhir', this.form).then(response => {
        this.isBusy = false
        this.show = true
        let getData = response.data
        this.data_siswa = getData.data.data_siswa
        this.data_tp = getData.data.data_tp
        var nilai = {}
        var kompeten = {}
        this.data_siswa.forEach(function(value, key) {
          nilai[value.anggota_rombel.anggota_rombel_id] = (value.anggota_rombel.nilai_akhir_mapel) ? value.anggota_rombel.nilai_akhir_mapel.nilai : ''
          value.anggota_rombel.capaian_kompeten.forEach(function(capaian, index) {
            kompeten[value.anggota_rombel.anggota_rombel_id+'#'+capaian.tp_id] = capaian.kompeten
          })
        })
        this.form.nilai = nilai
        this.form.kompeten = kompeten
      })
    },
    handleHideForm(){
      this.show = false
    },
    onSubmit(event) {
      event.preventDefault()
      this.isBusy = true
      this.$http.post('/penilaian/simpan-nilai-akhir', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.handleHideForm()
          this.onReset()
        })
      })
    },
    onReset() {
      //event.preventDefault()
      this.handleHideForm()
      this.form.tahun_pelajaran = this.user.semester.nama
      this.form.tingkat = ''
      this.form.jenis_rombel = ''
      this.form.rombongan_belajar_id = ''
      this.form.merdeka = false
      this.form.pembelajaran_id = ''
      this.form.mata_pelajaran_id = ''
      this.form.nilai = {}
      this.form.kompeten = {}
      this.data_siswa = []
      this.data_tp = []
      this.meta.tingkat_feedback = ''
      this.meta.tingkat_state = null
      this.meta.jenis_rombel_feedback = ''
      this.meta.jenis_rombel_state = null
      this.meta.rombongan_belajar_id_state = null
      this.meta.pembelajaran_id_state = null
      this.meta.rombongan_belajar_id_feedback = ''
      this.meta.pembelajaran_id_feedback = ''
      this.$refs.formulir.setValue();
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>