<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Cotizaciones</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Cotización</th>
                        <th>Comprobantes</th>
                        <!-- <th>Estado</th> -->
                        <th class="text-center">Moneda</th>
                        <!-- <th class="text-right">T.Exportación</th>
                        <th class="text-right">T.Gratuita</th>
                        <th class="text-right">T.Inafecta</th>
                        <th class="text-right">T.Exonerado</th> -->
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">PDF</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.identifier }} 
                        </td>
                        <td>
                            <template v-for="(document,i) in row.documents">
                                <label :key="i" v-text="document.number_full" class="d-block"></label>
                            </template>
                        </td>
                        <!-- <td>{{ row.state_type_description }}</td> -->
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <!-- <td class="text-right">{{ row.total_exportation }}</td>
                        <td class="text-right">{{ row.total_free }}</td>
                        <td class="text-right">{{ row.total_unaffected }}</td>
                        <td class="text-right">{{ row.total_exonerated }}</td> -->
                        <td class="text-right">{{ row.total_taxed }}</td>
                        <td class="text-right">{{ row.total_igv }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right"> 

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickOptionsPdf(row.id)">PDF</button>
                        </td>
                        
                        <td class="text-right">
                              <a v-if="row.documents.length == 0 && row.state_type_id != '11'" :href="`/${resource}/edit/${row.id}`" type="button" class="btn waves-effect waves-light btn-xs btn-info">Editar</a>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" 
                                    @click.prevent="clickOptions(row.id)">Generar comprobante</button>
                        </td>
                    </tr>
                </data-table>
            </div>
 

            <quotation-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showGenerate="true"
                              :showClose="true"></quotation-options>

            <quotation-options-pdf :showDialog.sync="showDialogOptionsPdf"
                              :recordId="recordId" 
                              :showClose="true"></quotation-options-pdf>
        </div>
    </div>
</template>

<script>
 
    import QuotationOptions from './partials/options.vue'
    import QuotationOptionsPdf from './partials/options_pdf.vue'
    import DataTable from '../../../components/DataTable.vue'

    export default { 
        components: {DataTable,QuotationOptions, QuotationOptionsPdf},
        data() {
            return { 
                resource: 'quotations',
                recordId: null,
                showDialogOptions: false,
                showDialogOptionsPdf: false
            }
        },
        created() {
        },
        methods: {  
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
            clickOptionsPdf(recordId = null) {
                this.recordId = recordId
                this.showDialogOptionsPdf = true
            }
        }
    }
</script>
