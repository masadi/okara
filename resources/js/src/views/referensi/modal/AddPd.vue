<template>
  <b-modal v-model="addPd" size="lg" title="Tambah Data Peserta Didik" @ok="handleOk" ok-title="Simpan" cancel-title="Tutup">
    <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-form @submit.prevent="handleSubmit">
      <b-row>
        <b-col cols="12">
          <b-form-group label="Nama Lengkap" label-cols-md="3" :invalid-feedback="feedback.nama" :state="state.nama">
            <b-form-input v-model="form.nama" :state="state.nama" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NIS" label-cols-md="3" :invalid-feedback="feedback.nis" :state="state.nis">
            <b-form-input v-model="form.nis" :state="state.nis" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NISN" label-cols-md="3" :invalid-feedback="feedback.nisn" :state="state.nisn">
            <b-form-input v-model="form.nisn" :state="state.nisn" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NIK" label-cols-md="3" :invalid-feedback="feedback.nik" :state="state.nik">
            <b-form-input v-model="form.nik" :state="state.nik" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Jenis Kelamin" label-cols-md="3" :invalid-feedback="feedback.jenis_kelamin" :state="state.jenis_kelamin">
            <v-select id="jenis_kelamin" v-model="form.jenis_kelamin" :reduce="nama => nama.id" label="nama" :options="data_kelamin" placeholder="== Pilih Jenis Kelamin ==" :state="state.jenis_kelamin">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tempat Lahir" label-cols-md="3" :invalid-feedback="feedback.tempat_lahir" :state="state.tempat_lahir">
            <b-form-input v-model="form.tempat_lahir" :state="state.tempat_lahir" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tanggal Lahir" label-cols-md="3" :invalid-feedback="feedback.tanggal_lahir" :state="state.tanggal_lahir">
            <b-form-datepicker v-model="form.tanggal_lahir" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_lahir" @context="onContext" placeholder="== Pilih Tanggal Lahir ==" :state="state.tanggal_lahir" />
            </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Agama" label-cols-md="3" :invalid-feedback="feedback.agama_id" :state="state.agama_id">
            <v-select id="agama_id" v-model="form.agama_id" :reduce="nama => nama.agama_id" label="nama" :options="data_agama" placeholder="== Pilih Agama ==" :searchable="false" :state="state.agama_id">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Status dalam keluarga" label-cols-md="3" :invalid-feedback="feedback.status" :state="state.status">
            <v-select id="status" v-model="form.status" :options="data_status" placeholder="== Pilih Status dalam keluarga ==" :searchable="false" :state="state.status">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Anak Ke" label-cols-md="3" :invalid-feedback="feedback.anak_ke" :state="state.anak_ke">
            <b-form-input v-model="form.anak_ke" :state="state.anak_ke" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Alamat" label-cols-md="3" :invalid-feedback="feedback.alamat" :state="state.alamat">
            <b-form-input v-model="form.alamat" :state="state.alamat" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="RT" label-cols-md="3" :invalid-feedback="feedback.rt" :state="state.rt">
            <b-form-input v-model="form.rt" :state="state.rt" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="RW" label-cols-md="3" :invalid-feedback="feedback.rw" :state="state.rw">
            <b-form-input v-model="form.rw" :state="state.rw" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Provinsi" label-cols-md="3" :invalid-feedback="feedback.provinsi" :state="state.provinsi">
            <v-select id="provinsi" v-model="form.provinsi" :reduce="nama => nama.kode_wilayah" label="nama" :options="data_provinsi" placeholder="== Pilih Provinsi ==" :state="state.provinsi" @input="changeProvinsi">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kabupaten/Kota" label-cols-md="3" :invalid-feedback="feedback.kabupaten" :state="state.kabupaten">
            <b-overlay :show="loading_kabupaten" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="kabupaten" v-model="form.kabupaten" :reduce="nama => nama.kode_wilayah" label="nama" :options="data_kabupaten" placeholder="== Pilih Kabupaten/Kota ==" :state="state.kabupaten" @input="changeKabupaten">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kecamatan" label-cols-md="3" :invalid-feedback="feedback.kecamatan" :state="state.kecamatan">
            <b-overlay :show="loading_kecamatan" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="kecamatan" v-model="form.kecamatan" :reduce="nama => nama.kode_wilayah" label="nama" :options="data_kecamatan" placeholder="== Pilih Kecamatan ==" :state="state.kecamatan" @input="changeKecamatan">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Desa/Kelurahan" label-cols-md="3" :invalid-feedback="feedback.desa_kelurahan" :state="state.desa_kelurahan">
            <b-overlay :show="loading_desa" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="desa_kelurahan" v-model="form.desa_kelurahan" :reduce="nama => nama.kode_wilayah" label="nama" :options="data_desa" placeholder="== Pilih Desa/Kelurahan ==" :state="state.desa_kelurahan">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kodepos" label-cols-md="3" :invalid-feedback="feedback.kode_pos" :state="state.kode_pos">
            <b-form-input v-model="form.kode_pos" :state="state.kode_pos" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Telp/HP" label-cols-md="3" :invalid-feedback="feedback.no_telp" :state="state.no_telp">
            <b-form-input v-model="form.no_telp" :state="state.no_telp" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Asal Sekolah" label-cols-md="3" :invalid-feedback="feedback.sekolah_asal" :state="state.sekolah_asal">
            <b-form-input v-model="form.sekolah_asal" :state="state.sekolah_asal" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Diterima dikelas" label-cols-md="3" :invalid-feedback="feedback.diterima_kelas" :state="state.diterima_kelas">
            <b-form-input v-model="form.diterima_kelas" :state="state.diterima_kelas" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Diterima pada tanggal" label-cols-md="3" :invalid-feedback="feedback.diterima" :state="state.diterima">
            <b-form-datepicker v-model="form.diterima" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="diterima" @context="onContext" placeholder="== Pilih Diterima pada tanggal ==" :state="state.diterima" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Email" label-cols-md="3" :invalid-feedback="feedback.email" :state="state.email">
            <b-form-input v-model="form.email" :state="state.email" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nama Ayah" label-cols-md="3" :invalid-feedback="feedback.nama_ayah" :state="state.nama_ayah">
            <b-form-input v-model="form.nama_ayah" :state="state.nama_ayah" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Pekerjaan Ayah" label-cols-md="3" :invalid-feedback="feedback.kerja_ayah" :state="state.kerja_ayah">
            <v-select id="status" v-model="form.kerja_ayah" :reduce="nama => nama.pekerjaan_id" label="nama" :options="pekerjaan" placeholder="== Pilih Pekerjaan Ayah ==" :searchable="false" :state="state.kerja_ayah">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nama Ibu Kandung" label-cols-md="3" :invalid-feedback="feedback.nama_ibu" :state="state.nama_ibu">
            <b-form-input v-model="form.nama_ibu" :state="state.nama_ibu" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Pekerjaan Ibu" label-cols-md="3" :invalid-feedback="feedback.kerja_ibu" :state="state.kerja_ibu">
            <v-select id="status" v-model="form.kerja_ibu" :reduce="nama => nama.pekerjaan_id" label="nama" :options="pekerjaan" placeholder="== Pilih Pekerjaan Ibu ==" :searchable="false" :state="state.kerja_ibu">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nama Wali" label-cols-md="3">
            <b-form-input v-model="form.nama_wali" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Alamat Wali" label-cols-md="3">
            <b-form-input v-model="form.alamat_wali" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Telp/HP Wali" label-cols-md="3">
            <b-form-input v-model="form.telp_wali" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Pekerjaan Wali" label-cols-md="3">
            <v-select id="kerja_wali" v-model="form.kerja_wali" :reduce="nama => nama.pekerjaan_id" label="nama" :options="pekerjaan" placeholder="== Pilih Pekerjaan Wali ==">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
      </b-row>
    </b-form>
  </b-overlay>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="secondary">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="primary">
          <b-button variant="primary" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-modal>
</template>

<script>
import { BOverlay, BRow, BCol, BForm, BFormGroup, BFormInput, BFormDatepicker, BButton } from 'bootstrap-vue'
import vSelect from 'vue-select'
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    BOverlay,
    BRow, BCol, BForm, BFormGroup, BFormInput, BFormDatepicker, BButton,
    vSelect
  },
  data() {
    return {
      loading_modal: false,
      addPd: false,
      form: {
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
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
      },
      feedback: {
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
      },
      state: {
        nama: null,
        nis: null,
        nisn: null,
        nik: null,
        jenis_kelamin: null,
        tempat_lahir: null,
        tanggal_lahir: null,
        agama_id: null,
        status: null,
        anak_ke: null,
        alamat: null,
        rt: null,
        rw: null,
        provinsi: null,
        kabupaten: null,
        kecamatan: null,
        desa_kelurahan: null,
        kecamatan: null,
        kode_pos: null,
        no_telp: null,
        sekolah_asal: null,
        diterima_kelas: null,
        diterima: null,
        email: null,
        nama_ayah: null,
        kerja_ayah: null,
        nama_ibu: null,
        kerja_ibu: null,
      },
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
      pekerjaan: [],
      data_status: ['Anak Kandung', 'Anak Tiri', 'Anak Angkat'],
      data_kelamin: [
        {
          id: 'L',
          nama: 'Laki-laki',
        },
        {
          id: 'P',
          nama: 'Perempuan',
        }
      ],
      loading_kabupaten: false,
      loading_kecamatan: false,
      loading_desa: false,
    }
  },
  created(){
    eventBus.$on('open-modal-add-pd', this.handleEvent);
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
  },
  methods: {
    handleEvent(){
      this.resetForm()
      this.$http.get('/peserta-didik/referensi').then(response => {
        let getData = response.data
        this.data_provinsi = getData.provinsi
        this.pekerjaan = getData.pekerjaan
        this.data_agama = getData.data_agama
        this.addPd = true
      });
    },
    resetForm(){
      this.form.nama = ''
      this.form.nis = ''
      this.form.nisn = ''
      this.form.nik = ''
      this.form.jenis_kelamin = ''
      this.form.tempat_lahir = ''
      this.form.tanggal_lahir = ''
      this.form.agama_id = ''
      this.form.status = ''
      this.form.anak_ke = ''
      this.form.alamat = ''
      this.form.rt = ''
      this.form.rw = ''
      this.form.provinsi = ''
      this.form.kabupaten = ''
      this.form.kecamatan = ''
      this.form.desa_kelurahan = ''
      this.form.kecamatan = ''
      this.form.kode_pos = ''
      this.form.no_telp = ''
      this.form.sekolah_asal = ''
      this.form.diterima_kelas = ''
      this.form.diterima = ''
      this.form.email = ''
      this.form.nama_ayah = ''
      this.form.kerja_ayah = ''
      this.form.nama_ibu = ''
      this.form.kerja_ibu = ''
      this.form.nama_wali = ''
      this.form.alamat_wali = ''
      this.form.telp_wali = ''
      this.form.kerja_wali = ''
      this.feedback.nama = ''
      this.feedback.nis = ''
      this.feedback.nisn = ''
      this.feedback.nik = ''
      this.feedback.jenis_kelamin = ''
      this.feedback.tempat_lahir = ''
      this.feedback.tanggal_lahir = ''
      this.feedback.agama_id = ''
      this.feedback.status = ''
      this.feedback.anak_ke = ''
      this.feedback.alamat = ''
      this.feedback.rt = ''
      this.feedback.rw = ''
      this.feedback.provinsi = ''
      this.feedback.kabupaten = ''
      this.feedback.kecamatan = ''
      this.feedback.desa_kelurahan = ''
      this.feedback.kecamatan = ''
      this.feedback.kode_pos = ''
      this.feedback.no_telp = ''
      this.feedback.sekolah_asal = ''
      this.feedback.diterima_kelas = ''
      this.feedback.diterima = ''
      this.feedback.email = ''
      this.feedback.nama_ayah = ''
      this.feedback.kerja_ayah = ''
      this.feedback.nama_ibu = ''
      this.feedback.kerja_ibu = ''
      this.state.nama = null
      this.state.nis = null
      this.state.nisn = null
      this.state.nik = null
      this.state.jenis_kelamin = null
      this.state.tempat_lahir = null
      this.state.tanggal_lahir = null
      this.state.agama_id = null
      this.state.status = null
      this.state.anak_ke = null
      this.state.alamat = null
      this.state.rt = null
      this.state.rw = null
      this.state.provinsi = null
      this.state.kabupaten = null
      this.state.kecamatan = null
      this.state.desa_kelurahan = null
      this.state.kecamatan = null
      this.state.kode_pos = null
      this.state.no_telp = null
      this.state.sekolah_asal = null
      this.state.diterima_kelas = null
      this.state.diterima = null
      this.state.email = null
      this.state.nama_ayah = null
      this.state.kerja_ayah = null
      this.state.nama_ibu = null
      this.state.kerja_ibu = null
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.$emit('loadingModal', true)
      this.$http.post('/peserta-didik/simpan', this.form).then(response => {
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
            this.addPd = false
            this.$emit('per_page', 10)
            this.resetForm()
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
    },
  },
}
</script>