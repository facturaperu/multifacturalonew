<template>
  <el-dialog
    :title="titleDialog"
    :visible="showDialog"
    @close="close"
    @open="create"
    class="dialog-import"
  >
    <form autocomplete="off" @submit.prevent="submit">
      <div class="form-body">
        <div class="row">
          <div class="col-md-12 mt-4">
            <div class="form-group text-center" :class="{'has-danger': errors.file}">
              <el-upload
                action="''"
                ref="upload"
                :show-file-list="true"
                :auto-upload="false"
                :multiple="false"
                :on-change="handleChange"
                :limit="1"
              >
                <el-button slot="trigger" type="primary">Seleccione un archivo (xml)</el-button>
              </el-upload>
              <small class="form-control-feedback" v-if="errors.file" v-text="errors.file[0]"></small>
            </div>
          </div>
        </div>
      </div>
      <div class="form-actions text-right mt-4">
        <el-button @click.prevent="close()">Cancelar</el-button>
        <el-button type="primary" native-type="submit" :loading="loading_submit">Procesar</el-button>
      </div>
    </form>
  </el-dialog>
</template>

<script>
import { calculateRowItem } from "../../../helpers/functions";

export default {
  props: ["showDialog"],
  data() {
    return {
      loading_submit: false,
      headers: headers_token,
      titleDialog: null,
      resource: "documents",
      errors: {},
      form: {},
      formXmlJson: {},
      items: [],
      affectation_igv_types: [],
      system_isc_types: [],
      discount_types: [],
      charge_types: [],
      attribute_types: [],
      warehouses: []
    };
  },
  created() {
    // this.$http.get(`/${this.resource}/item/tables`).then(response => {
    //   this.items = response.data.items;
    //   this.affectation_igv_types = response.data.affectation_igv_types;
    //   this.system_isc_types = response.data.system_isc_types;
    //   this.discount_types = response.data.discount_types;
    //   this.charge_types = response.data.charge_types;
    //   this.attribute_types = response.data.attribute_types;
    //   this.warehouses = response.data.warehouses;
    // });
    this.initForm();
  },
  methods: {
    handleChange(file) {
      const self = this;
      const reader = new FileReader();
      reader.onload = e => self.parseXml(e.target.result);
      reader.readAsText(file.raw);
    },
    async parseXml(source) {
      this.loading_submit = true;
      let convert = require("xml-js");
      this.formXmlJson = convert.xml2js(source, { compact: true, spaces: 4 });

      let Invoice = this.formXmlJson.Invoice;
      this.form.is_item_array = Array.isArray(Invoice["cac:InvoiceLine"])
      this.form.xml = this.formXmlJson

      // console.log(this.formXmlJson)

      // await this.setdataForm();
      this.loading_submit = false;
    },   
    initForm() {

      this.errors = {}; 
      this.form = {
          xml: null,
          is_item_array: false, 
      }
      //this.initInputPerson();
    },
    create() {
      this.titleDialog = "Importar Factura";
    },
    async submit() {
      this.loading_submit = true;
      // console.log(this.form)

      await this.$http
        .post(`/${this.resource}/import-xml`, this.form)
        .then(response => {
          if (response.data.success) {
            this.$message.success(response.data.message);
            this.$eventHub.$emit("reloadData");
            this.$refs.upload.clearFiles();
            this.close();
          } else {
            this.$message({ message: response.data.message, type: "error" });
          }
        })
        .catch(error => {
          this.$message.error(error.response.message);
        })
        .then(() => {
          this.loading_submit = false;
        });
    },
    close() {
      this.$emit("update:showDialog", false);
      this.initForm();
    },
    successUpload(response, file, fileList) {
      if (response.success) {
        //this.$message.success(response.message)
        //this.$eventHub.$emit('reloadData')
        //this.$refs.upload.clearFiles()
        //this.close()
      } else {
        this.$message({ message: response.message, type: "error" });
      }
    },
    errorUpload(response) {
      console.log(response);
    },
    xmlToJson(xml) {
      // Create the return object
      var obj = {};

      if (xml.nodeType == 1) {
        // element
        // do attributes
        if (xml.attributes.length > 0) {
          obj["@attributes"] = {};
          for (var j = 0; j < xml.attributes.length; j++) {
            var attribute = xml.attributes.item(j);
            obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
          }
        }
      } else if (xml.nodeType == 3) {
        // text
        obj = xml.nodeValue;
      }

      // do children
      // If all text nodes inside, get concatenated text from them.
      var textNodes = [].slice.call(xml.childNodes).filter(function(node) {
        return node.nodeType === 3;
      });
      if (xml.hasChildNodes() && xml.childNodes.length === textNodes.length) {
        obj = [].slice.call(xml.childNodes).reduce(function(text, node) {
          return text + node.nodeValue;
        }, "");
      } else if (xml.hasChildNodes()) {
        for (var i = 0; i < xml.childNodes.length; i++) {
          var item = xml.childNodes.item(i);
          var nodeName = item.nodeName;
          if (typeof obj[nodeName] == "undefined") {
            obj[nodeName] = this.xmlToJson(item);
          } else {
            if (typeof obj[nodeName].push == "undefined") {
              var old = obj[nodeName];
              obj[nodeName] = [];
              obj[nodeName].push(old);
            }
            obj[nodeName].push(this.xmlToJson(item));
          }
        }
      }
      return obj;
    },
    demo() {
      parseXMLToJSON();
      return false;
    }
  }
};
</script>
