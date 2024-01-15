<template>
  <div>
    <b-row v-if="status === 'aktif'">
      <b-col md="4" class="mb-2">
        <v-select id="tingkat" v-model="filter.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Filter Tingkat Kelas ==" :searchable="false" @input="changeTingkat">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-col>
      <b-col md="4">
        <v-select id="tingkat" v-model="filter.jurusan_sp_id" :reduce="nama_jurusan_sp => nama_jurusan_sp.jurusan_sp_id" label="nama_jurusan_sp" :options="data_jurusan" placeholder="== Filter Jurusan ==" @input="changeJurusan">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-col>
      <b-col md="4">
        <v-select id="tingkat" v-model="filter.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Filter Rombel ==" @input="changeRombel">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
      </b-col>
      <b-col md="4" offset-md="4">
        <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(ttl)="row">
          {{row.item.tempat_lahir}}, {{row.item.tanggal_lahir_indo}}
        </template>
        <template v-slot:cell(agama)="row">
          {{(row.item.agama) ? row.item.agama.nama : '-'}}
        </template>
        <template v-slot:cell(anggota_rombel)="row">
          {{(row.item.anggota_rombel && row.item.anggota_rombel.rombongan_belajar) ? row.item.anggota_rombel.rombongan_belajar.nama : '-'}}
        </template>
        <template v-slot:cell(actions)="row">
          <b-button variant="success" size="sm" @click="getDetil(row.item.peserta_didik_id)">Detil</b-button>
        </template>
        <template v-slot:cell(user)="row">
          <template v-if="row.item.user">
            <template v-if="cekPass(row.item.user.password, row.item.user.default_password)">
              {{row.item.user.default_password}}
            </template>
            <template v-else>
              <b-badge variant="success">Custom</b-badge>
            </template>
          </template>
        </template>
      </b-table>
    </b-overlay>
    <b-row class="mt-2">
      <b-col md="6">
        <p>Menampilkan {{ (meta.from) ? meta.from : 0 }} sampai {{ meta.to }} dari {{ meta.total }} entri</p>
      </b-col>
      <b-col md="6">
        <b-pagination v-model="meta.current_page" :total-rows="meta.total" :per-page="meta.per_page" align="right" @change="changePage" aria-controls="dw-datatable"></b-pagination>
      </b-col>
    </b-row>
    <b-modal ref="detil-modal" size="lg" :title="title" @ok="handleOk" ok-title="Perbaharui" cancel-title="Tutup">
      <detil-pd :data="data" :loading_modal="loading_modal" :form="form" :data_desa="data_desa" :data_kecamatan="data_kecamatan" :data_kabupaten="data_kabupaten" :data_provinsi="data_provinsi" :data_agama="data_agama" :pekerjaan="pekerjaan" :feedback="feedback" :state="state" @provinsi="changeProvinsi" @kabupaten="changeKabupaten" @kecamatan="changeKecamatan" :loading_kabupaten="loading_kabupaten" :loading_kecamatan="loading_kecamatan" :loading_desa="loading_desa"></detil-pd>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="secondary">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="primary">
          <b-button variant="primary" @click="ok()">Perbaharui</b-button>
        </b-overlay>
      </template>
    </b-modal>
  </div>
</template>

<script>
import _ from 'lodash'
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BButton, BOverlay } from 'bootstrap-vue'
import DetilPd from './../modal/DetilPd.vue'
import vSelect from 'vue-select'
import bcrypt from 'bcryptjs';
export default {
  components: {
    BRow,
    BCol,
    BFormInput,
    BTable,
    BSpinner,
    BPagination,
    BButton,
    BOverlay,
    DetilPd,
    vSelect,
  },
  props: {
    status: {
      type: String,
      required: true
    },
    items: {
      type: Array,
      required: true
    },
    fields: {
      type: Array,
      required: true
    },
    meta: {
      required: true
    },
    isBusy: {
      type: Boolean,
      default: () => true,
    },
    loading: {
      type: Boolean,
      default: () => false,
    },
    loading_modal: {
      type: Boolean,
      default: () => false,
    },
    filter: {
      type: Object,
      required: true
    },
    data_jurusan: {
      type: Array,
    },
    data_rombel: {
      type: Array,
    },
  },
  data() {
    return {
      sortBy: null,
      sortDesc: false,
      title: '',
      data: null,
      pekerjaan: [],
      form: {
        peserta_didik_id: '',
        /*status: '',
        anak_ke: '',
        diterima_kelas: '',
        email: '',
        nama_wali: '',
        alamat_wali: '',
        telp_wali: '',
        kerja_wali: '',*/
        nama: '',
        nis: '',
        nisn: '',
        nik: '',
        jenis_kelamin: '',
        tempat_lahir: '',
        tanggal_lahir: '',
        agama_id: '',
        status: '',
        anak_ke: '',
        alamat: '',
        rt: '',
        rw: '',
        provinsi: '',
        kabupaten: '',
        kecamatan: '',
        desa_kelurahan: '',
        kecamatan: '',
        kode_pos: '',
        no_telp: '',
        sekolah_asal: '',
        diterima_kelas: '',
        diterima: '',
        email: '',
        nama_ayah: '',
        kerja_ayah: '',
        nama_ibu: '',
        kerja_ibu: '',
        nama_wali: '',
        alamat_wali: '',
        telp_wali: '',
        kerja_wali: '',
      },
      feedback: {},
      state: {},
      data_tingkat: [
        {
          id: 1,
          nama: 'Kelas 1',
        },
        {
          id: 2,
          nama: 'Kelas 2',
        },
        {
          id: 3,
          nama: 'Kelas 3',
        },
        {
          id: 4,
          nama: 'Kelas 4',
        },
        {
          id: 5,
          nama: 'Kelas 5',
        },
        {
          id: 6,
          nama: 'Kelas 6',
        },
        {
          id: 7,
          nama: 'Kelas 7',
        },
        {
          id: 8,
          nama: 'Kelas 8',
        },
        {
          id: 9,
          nama: 'Kelas 9',
        },
        {
          id: 10,
          nama: 'Kelas 10',
        },
        {
          id: 11,
          nama: 'Kelas 11',
        },
        {
          id: 12,
          nama: 'Kelas 12',
        },
        {
          id: 13,
          nama: 'Kelas 13',
        },
      ],
      data_desa: [],
      data_kecamatan: [],
      data_kabupaten: [],
      data_provinsi: [],
      data_agama: [],
      loading_kabupaten: false,
      loading_kecamatan: false,
      loading_desa: false,
    }
  },
  watch: {
    sortBy(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    },
    sortDesc(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    }
  },
  methods: {
    cekPass(password, default_password){
      return bcrypt.compareSync(default_password, password)
    },
    getDetil(peserta_didik_id){
      this.$emit('loadingTable', true)
      this.$emit('loadingModal', true)
      this.form.peserta_didik_id = peserta_didik_id
      this.$http.post('/peserta-didik/detil', this.form).then(response => {
        this.$emit('loadingTable', false)
        this.$emit('loadingModal', false)
        var getData = response.data
        this.data = getData.data
        this.pekerjaan = getData.pekerjaan
        this.form.nama = this.data.nama
        this.form.nis = this.data.no_induk
        this.form.nisn = this.data.nisn
        this.form.nik = this.data.nik
        this.form.jenis_kelamin = this.data.jenis_kelamin
        this.form.tempat_lahir = this.data.tempat_lahir
        this.form.tanggal_lahir = this.data.tanggal_lahir
        this.form.agama_id = this.data.agama_id
        this.form.status = this.data.status
        this.form.anak_ke = this.data.anak_ke
        this.form.alamat = this.data.alamat
        this.form.rt = this.data.rt
        this.form.rw = this.data.rw
        this.form.provinsi = this.data.wilayah.parrent_recursive.parrent_recursive.parrent_recursive.kode_wilayah
        this.form.kabupaten = this.data.wilayah.parrent_recursive.parrent_recursive.kode_wilayah
        this.form.kecamatan = this.data.wilayah.parrent_recursive.kode_wilayah
        this.form.desa_kelurahan = this.data.kode_wilayah
        this.form.kode_pos = this.data.kode_pos
        this.form.no_telp = this.data.no_telp
        this.form.sekolah_asal = this.data.sekolah_asal
        this.form.diterima_kelas = this.data.diterima_kelas
        this.form.diterima = this.data.diterima
        this.form.email = this.data.email
        this.form.nama_ayah = this.data.nama_ayah
        this.form.kerja_ayah = this.data.kerja_ayah
        this.form.nama_ibu = this.data.nama_ibu
        this.form.kerja_ibu = this.data.kerja_ibu
        this.form.nama_wali = this.data.nama_wali
        this.form.alamat_wali = this.data.alamat_wali
        this.form.telp_wali = this.data.telp_wali
        this.form.kerja_wali = this.data.kerja_wali
        this.title = 'Detil '+getData.data.nama
        this.data_provinsi = getData.provinsi
        this.data_kabupaten = getData.kabupaten
        this.data_kecamatan = getData.kecamatan
        this.data_desa = getData.desa
        this.data_agama = getData.data_agama
        this.$refs['detil-modal'].show()
      //}).catch(error => {
        //console.log(error);
      })
    },
    loadPerPage(val) {
      this.$emit('per_page', this.meta.per_page)
    },
    changePage(val) {
      this.$emit('pagination', val)
    },
    changeTingkat(val) {
      this.$emit('tingkat', val)
    },
    changeJurusan(val) {
      this.$emit('jurusan', val)
    },
    changeRombel(val) {
      this.$emit('rombel', val)
    },
    search: _.debounce(function (e) {
      this.$emit('search', e)
    }, 500),
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.$emit('loadingModal', true)
      this.$http.post('/peserta-didik/update', this.form).then(response => {
        let data = response.data
        this.$emit('loadingModal', false)
        if(data.errors){
          this.state.nama= (data.errors.nama) ? false : null
          this.state.nis= (data.errors.nis) ? false : null
          this.state.nisn= (data.errors.nisn) ? false : null
          this.state.nik= (data.errors.nik) ? false : null
          this.state.jenis_kelamin= (data.errors.jenis_kelamin) ? false : null
          this.state.tempat_lahir= (data.errors.tempat_lahir) ? false : null
          this.state.tanggal_lahir= (data.errors.tanggal_lahir) ? false : null
          this.state.agama_id= (data.errors.agama_id) ? false : null
          this.state.status= (data.errors.status) ? false : null
          this.state.anak_ke= (data.errors.anak_ke) ? false : null
          this.state.alamat= (data.errors.alamat) ? false : null
          this.state.rt= (data.errors.rt) ? false : null
          this.state.rw= (data.errors.rw) ? false : null
          this.state.provinsi= (data.errors.provinsi) ? false : null
          this.state.kabupaten= (data.errors.kabupaten) ? false : null
          this.state.kecamatan= (data.errors.kecamatan) ? false : null
          this.state.desa_kelurahan= (data.errors.desa_kelurahan) ? false : null
          this.state.kecamatan= (data.errors.kecamatan) ? false : null
          this.state.kode_pos= (data.errors.kode_pos) ? false : null
          this.state.no_telp= (data.errors.no_telp) ? false : null
          this.state.sekolah_asal= (data.errors.sekolah_asal) ? false : null
          this.state.diterima_kelas= (data.errors.diterima_kelas) ? false : null
          this.state.diterima= (data.errors.diterima) ? false : null
          this.state.email= (data.errors.email) ? false : null
          this.state.nama_ayah= (data.errors.nama_ayah) ? false : null
          this.state.kerja_ayah= (data.errors.kerja_ayah) ? false : null
          this.state.nama_ibu= (data.errors.nama_ibu) ? false : null
          this.state.kerja_ibu= (data.errors.kerja_ibu) ? false : null
          this.feedback.nama= (data.errors.nama) ? data.errors.nama.join(', ') : ''
          this.feedback.nis= (data.errors.nis) ? data.errors.nis.join(', ') : ''
          this.feedback.nisn= (data.errors.nisn) ? data.errors.nisn.join(', ') : ''
          this.feedback.nik= (data.errors.nik) ? data.errors.nik.join(', ') : ''
          this.feedback.jenis_kelamin= (data.errors.jenis_kelamin) ? data.errors.jenis_kelamin.join(', ') : ''
          this.feedback.tempat_lahir= (data.errors.tempat_lahir) ? data.errors.tempat_lahir.join(', ') : ''
          this.feedback.tanggal_lahir= (data.errors.tanggal_lahir) ? data.errors.tanggal_lahir.join(', ') : ''
          this.feedback.agama_id= (data.errors.agama_id) ? data.errors.agama_id.join(', ') : ''
          this.feedback.status= (data.errors.status) ? data.errors.status.join(', ') : ''
          this.feedback.anak_ke= (data.errors.anak_ke) ? data.errors.anak_ke.join(', ') : ''
          this.feedback.alamat= (data.errors.alamat) ? data.errors.alamat.join(', ') : ''
          this.feedback.rt= (data.errors.rt) ? data.errors.rt.join(', ') : ''
          this.feedback.rw= (data.errors.rw) ? data.errors.rw.join(', ') : ''
          this.feedback.provinsi= (data.errors.provinsi) ? data.errors.provinsi.join(', ') : ''
          this.feedback.kabupaten= (data.errors.kabupaten) ? data.errors.kabupaten.join(', ') : ''
          this.feedback.kecamatan= (data.errors.kecamatan) ? data.errors.kecamatan.join(', ') : ''
          this.feedback.desa_kelurahan= (data.errors.desa_kelurahan) ? data.errors.desa_kelurahan.join(', ') : ''
          this.feedback.kecamatan= (data.errors.kecamatan) ? data.errors.kecamatan.join(', ') : ''
          this.feedback.kode_pos= (data.errors.kode_pos) ? data.errors.kode_pos.join(', ') : ''
          this.feedback.no_telp= (data.errors.no_telp) ? data.errors.no_telp.join(', ') : ''
          this.feedback.sekolah_asal= (data.errors.sekolah_asal) ? data.errors.sekolah_asal.join(', ') : ''
          this.feedback.diterima_kelas= (data.errors.diterima_kelas) ? data.errors.diterima_kelas.join(', ') : ''
          this.feedback.diterima= (data.errors.diterima) ? data.errors.diterima.join(', ') : ''
          this.feedback.email= (data.errors.email) ? data.errors.email.join(', ') : ''
          this.feedback.nama_ayah= (data.errors.nama_ayah) ? data.errors.nama_ayah.join(', ') : ''
          this.feedback.kerja_ayah= (data.errors.kerja_ayah) ? data.errors.kerja_ayah.join(', ') : ''
          this.feedback.nama_ibu= (data.errors.nama_ibu) ? data.errors.nama_ibu.join(', ') : ''
          this.feedback.kerja_ibu= (data.errors.kerja_ibu) ? data.errors.kerja_ibu.join(', ') : ''
        } else {
          this.$swal({
            icon: data.icon,
            title: data.title,
            text: data.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.$refs['detil-modal'].hide()
            this.loadPerPage(this.meta.per_page)
          })
        }
      })
    },
    changeProvinsi(val){
      this.form.kabupaten = ''
      this.form.kecamatan = ''
      this.form.desa_kelurahan = ''
      if(val){
        this.loading_kabupaten = true
        this.loading_kecamatan = true
        this.loading_desa = true
        this.$http.post('/peserta-didik/kabupaten', this.form).then(response => {
          this.loading_kabupaten = false
          this.loading_kecamatan = false
          this.loading_desa = false
          this.data_kabupaten = response.data
        })
      }
    },
    changeKabupaten(val){
      this.form.kecamatan = ''
      this.form.desa_kelurahan = ''
      if(val){
        this.loading_kecamatan = true
        this.loading_desa = true
        this.$http.post('/peserta-didik/kecamatan', this.form).then(response => {
          this.loading_kecamatan = false
          this.loading_desa = false
          this.data_kecamatan = response.data
        })
      }
    },
    changeKecamatan(val){
      this.form.desa_kelurahan = ''
      if(val){
        this.loading_desa = true
        this.$http.post('/peserta-didik/desa', this.form).then(response => {
          this.loading_desa = false
          this.data_desa = response.data
        })
      }
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>