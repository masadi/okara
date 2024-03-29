export default [
    {
      icon: 'list-check',
      title: 'Nilai Akademik',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Input Nilai Akhir',
          route: 'penilaian-nilai-akhir',
          resource: 'Guru',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Capaian Kompetensi',
          route: 'penilaian-capaian-kompetensi',
          resource: 'Guru',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Penilaian Sikap',
          route: 'penilaian-sikap',
          resource: 'Guru',
          action: 'read',
        },
      ]
    },
  ]
  