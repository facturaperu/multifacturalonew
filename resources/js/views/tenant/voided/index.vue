<template>
    <div>
        <header class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Anulaciones</span></li>
            </ol>
        </header>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de anulaciones</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Comprobante</th>
                        <th>Motivo de anulación</th>
                        <th>Ticket</th>
                        <th>Estado</th>
                        <th class="text-center">Descargas</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" :class="{'text-danger': (row.state_type_id === '11'), 'text-warning': (row.state_type_id === '13')}">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.document_type_description }} {{ row.number }}</td>
                        <td>{{ row.voided_description }}</td>
                        <td>{{ row.ticket }}</td>
                        <td>{{ row.state_type_description }}</td>
                        <td class="text-center">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_xml)"
                                    v-if="row.has_xml">XML</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_pdf)"
                                    v-if="row.has_pdf">PDF</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_cdr)"
                                    v-if="row.has_cdr">CDR</button>
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                    @click.prevent="clickTicket(row.id)"
                                    v-if="row.btn_ticket">Consultar</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
    </div>

</template>

<script>

    import DataTable from '../../../components/DataTable.vue'

    export default {
        components: {DataTable},
        data () {
            return {
                resource: 'voided',
                showDialog: false,
                records: [],
            }
        },
        created() {
        },
        methods: {
            clickTicket(id) {
                this.$http.get(`/${this.resource}/ticket/${id}`)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                        } else {
                            this.$message.error('Error al reenviar el archivo xml')
                        }
                    })
                    .catch(error => {
                        this.$message.error(error.response.data.message)
                    })
            },
            clickDownload(download) {
                window.open(download, '_blank');
            },
        }
    }
</script>
