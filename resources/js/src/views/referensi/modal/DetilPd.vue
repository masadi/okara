<template>
  <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-form @submit.prevent="handleSubmit">
      <b-row v-if="data">
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
            <!--b-form-select v-model="form.form.kerja_wali" :options="pekerjaan" text-field="nama" value-field="pekerjaan_id">
              <template #first>
                <b-form-select-option value="">== Pilih Pekerjaan Wali ==</b-form-select-option>
              </template>
            </b-form-select-->
          </b-form-group>
        </b-col>
      </b-row>
    </b-form>
  </b-overlay>
</template>

<script>
import { BOverlay, BRow, BCol, BForm, BFormGroup, BFormInput, BFormDatepicker } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BRow, BCol, BForm, BFormGroup, BFormInput, BFormDatepicker,
    vSelect
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    feedback: {
      type: Object,
      required: true
    },
    state: {
      type: Object,
      required: true
    },
    data: {
      required: true
    },
    data_desa: {
      type: Array,
      required: true
    },
    data_kecamatan: {
      type: Array,
      required: true
    },
    data_kabupaten: {
      type: Array,
      required: true
    },
    data_provinsi: {
      type: Array,
      required: true
    },
    data_agama: {
      type: Array,
      required: true
    },
    pekerjaan: {
      type: Array,
      required: true
    },
    loading_modal: {
      type: Boolean,
      default: () => false,
    },
    loading_kabupaten: {
      type: Boolean,
      default: () => false,
    },
    loading_kecamatan: {
      type: Boolean,
      default: () => false,
    },
    loading_desa: {
      type: Boolean,
      default: () => false,
    },
  },
  data() {
    return {
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
    }
  },
  methods: {
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
    changeProvinsi(val){
      this.$emit('provinsi', val)
    },
    changeKabupaten(val){
      this.$emit('kabupaten', val)
    },
    changeKecamatan(val){
      this.$emit('kecamatan', val)
    }
  },
}
</script>