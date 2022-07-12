<template>
    <div class="tab">
        <button class="tablinks" @click="activetab = 'general'">
            General Information
        </button>
        <button class="tablinks" @click="activetab = 'codicology'">
            Codicology
        </button>
        <button class="tablinks" @click="activetab = 'conservation'">
            Conservation and Analysis
        </button>
        <button class="tablinks" @click="activetab = 'provenance'">
            Provenance
        </button>
        <button class="tablinks" @click="activetab = 'images'">Images</button>
    </div>
    <form @submit.prevent="submit">
        <fieldset v-if="activetab == 'general'" class="container">
            <legend>General Information</legend>
            <input type="hidden" input_id="id" v-model="form.id" />
            <EthInput
                input_type="text"
                input_id="standard_name"
                v-model="form.standard_name"
                >Standard Name</EthInput
            >
            <EthInput
                input_type="textarea"
                input_id="other_names"
                v-model="form.other_names"
                >Other Names</EthInput
            >
            <EthInput
                input_type="text"
                input_id="publication"
                v-model="form.publication"
                >Publication</EthInput
            >
            <EthInput
                input_type="text"
                input_id="current_shelfmarks"
                v-model="form.current_shelfmarks"
                >Current Shelfmarks</EthInput
            >
            <EthInput
                input_type="text"
                input_id="trismegistos_id"
                v-model="form.trismegistos_id"
                >Trismegistos ID</EthInput
            >

            <EthInput input_type="text" input_id="title" v-model="form.title"
                >Title</EthInput
            >
            <EthInput
                input_type="text"
                input_id="ancient_author"
                v-model="form.ancient_author"
                >Author</EthInput
            >

            <EthInput
                input_type="multi_choice"
                input_id="genres"
                :choices="genres_all"
                v-model="form.genres"
            >
                Genre
            </EthInput>

            <EthInput
                input_type="multi_choice"
                input_id="tags"
                :choices="tags_all"
                v-model="form.tags"
            >
                Tags
            </EthInput>

            <EthInput
                input_type="textarea"
                input_id="content_description"
                v-model="form.content_description"
                >Description of Content</EthInput
            >

            <EthInput
                input_type="multi_choice"
                input_id="languages"
                :choices="languages_all"
                v-model="form.languages"
            >
                Languages
            </EthInput>

            <EthInput
                input_type="year"
                input_id="start_year"
                v-model="form.start_year"
                >Earliest Date</EthInput
            >
            <EthInput
                input_type="year"
                input_id="end_year"
                v-model="form.end_year"
                >Latest Date</EthInput
            >

            <EthInput
                input_type="single_choice"
                input_id="dating_method"
                :choices="dating_methods"
                v-model="form.dating_method_id"
            >
                Basis of Date
            </EthInput>
            <EthInput
                input_type="single_choice"
                input_id="dating_certainty"
                :choices="dating_certainties"
                v-model="form.dating_certainty_id"
            >
                Certainty of Date
            </EthInput>
            <EthInput
                input_type="textarea"
                input_id="dating_comment"
                v-model="form.dating_comment"
                >Comments on Date</EthInput
            >
            <EthInput
                input_type="textarea"
                input_id="bibliography"
                v-model="form.bibliography"
                >Bibliography</EthInput
            >
            <EthInput
                input_type="textarea"
                input_id="internal_comment"
                v-model="form.internal_comment"
                >Internal Comments</EthInput
            >
            <EthInput
                input_type="textarea"
                input_id="general_comment"
                v-model="form.general_comment"
                >General Comments</EthInput
            >
            <button @click.prevent="submit">Store All Changes</button>
        </fieldset>

        <fieldset v-if="activetab == 'codicology'" class="container">
            <legend>Codicology</legend>

            <EthInput
                input_type="single_choice"
                input_id="material"
                :choices="materials"
                v-model="form.material_id"
            >
                Material
            </EthInput>

            <EthInput
                input_type="text"
                input_id="full_page_width"
                v-model="form.full_page_width"
                >Full Page Width</EthInput
            >
            <EthInput
                input_type="text"
                input_id="full_page_height"
                v-model="form.full_page_height"
                >Full Page Height</EthInput
            >
            <EthRatio
                :dividend="form.full_page_width"
                :divisor="form.full_page_height"
                >Full Page Width/Height Ratio</EthRatio
            >

            <EthInput
                input_type="text"
                input_id="upper_margin"
                v-model="form.upper_margin"
                >Upper Margin</EthInput
            >
            <EthInput
                input_type="text"
                input_id="lower_margin"
                v-model="form.lower_margin"
                >Lower Margin</EthInput
            >
            <EthRatio :dividend="form.upper_margin" :divisor="form.lower_margin"
                >Upper/Lower Margin Ratio</EthRatio
            >

            <EthInput
                input_type="text"
                input_id="inner_margin"
                v-model="form.inner_margin"
                >Inner Margin</EthInput
            >
            <EthInput
                input_type="text"
                input_id="outer_margin"
                v-model="form.outer_margin"
                >Outer Margin</EthInput
            >
            <EthRatio :dividend="form.inner_margin" :divisor="form.outer_margin"
                >Inner/Outer Margin Ratio</EthRatio
            >

            <EthInput
                input_type="text"
                input_id="full_text_block_width"
                v-model="form.full_text_block_width"
                >Full Text Block Width</EthInput
            >
            <EthInput
                input_type="text"
                input_id="full_text_block_height"
                v-model="form.full_text_block_height"
                >Full Text Block Height</EthInput
            >
            <EthRatio
                :dividend="form.full_text_block_width"
                :divisor="form.full_text_block_height"
                >Full Text Block Width/Height Ratio</EthRatio
            >

            <EthInput
                input_type="textarea"
                input_id="measurement_comment"
                v-model="form.measurement_comment"
                >Comments on Measurements</EthInput
            >

            <EthInput
                input_type="multi_choice"
                input_id="scripts"
                :choices="scripts_all"
                v-model="form.scripts"
            >
                Scripts
            </EthInput>

            <EthInput
                input_type="number"
                input_id="hand_number"
                v-model="form.hand_number"
                >Number of Hands</EthInput
            >
            <EthInput
                input_type="textarea"
                input_id="script_description"
                v-model="form.script_description"
                >Script Description</EthInput
            >

            <EthInput
                input_type="multi_choice"
                input_id="paratexts"
                :choices="paratexts_all"
                v-model="form.paratexts"
            >
                Paratexts
            </EthInput>

            <EthInput
                input_type="multi_choice"
                input_id="decorations"
                :choices="decorations_all"
                v-model="form.decorations"
            >
                Decorations
            </EthInput>

            <EthInput
                input_type="single_choice"
                input_id="pagination"
                :choices="paginations"
                v-model="form.pagination_id"
            >
                Pagination
            </EthInput>

            <EthInput
                input_type="single_choice"
                input_id="cover"
                :choices="covers"
                v-model="form.cover_id"
            >
                Cover
            </EthInput>

            <EthInput
                input_type="single_choice"
                input_id="ink"
                :choices="inks"
                v-model="form.ink_id"
            >
                Ink
            </EthInput>

            <EthInput
                input_type="single_choice"
                input_id="quire_signature"
                :choices="quire_signatures"
                v-model="form.quire_signature_id"
            >
                Quire Signatures
            </EthInput>

            <EthInput
                input_type="single_choice"
                input_id="quire_structure"
                :choices="quire_structures"
                v-model="form.quire_structure_id"
            >
                Quire Structure
            </EthInput>

            <div v-if="form.quire_structure_id == 1">
                <EthInput
                    input_type="bifolia"
                    input_id="bifolia"
                    :num="1"
                    v-model="form.bifolia"
                >
                    Number of Bifolia in the Quire
                </EthInput>
            </div>

            <div v-if="form.quire_structure_id == 2">
                <EthInput
                    input_type="number"
                    input_id="quire_number"
                    v-model="form.quire_number"
                    >Number of Quires</EthInput
                >
                <EthInput
                    input_type="bifolia"
                    input_id="bifolia"
                    :num="parseInt(form.quire_number)"
                    v-model="form.bifolia"
                >
                    Number of Bifolia in each Quire
                </EthInput>
            </div>

            <div v-if="form.quire_structure_id == 3">
                <EthInput
                    input_type="number"
                    input_id="quire_number"
                    v-model="form.quire_number"
                    >Number of Quires</EthInput
                >
                <EthInput
                    input_type="bifolia"
                    input_id="bifolia"
                    :num="1"
                    v-model="form.bifolia"
                >
                    Number of Bifolia in the Quires
                </EthInput>
            </div>

            <EthInput
                input_type="textarea"
                input_id="quire_comment"
                v-model="form.quire_comment"
                >Comments on Quire(s)</EthInput
            >
            <EthInput
                input_type="textarea"
                input_id="binding_description"
                v-model="form.binding_description"
                >Binding Description</EthInput
            >
            <button @click="submit">Store All Changes</button>
        </fieldset>

        <fieldset v-if="activetab == 'conservation'" class="container">
            <legend>Conservation and Analysis</legend>

            <EthInput
                input_type="single_choice"
                input_id="storage_condition"
                :choices="storage_conditions"
                v-model="form.storage_condition_id"
            >
                Storage Condition
            </EthInput>

            <EthInput
                input_type="textarea"
                input_id="conservation_history"
                v-model="form.conservation_history"
                >Conservation History</EthInput
            >

            <EthInput
                input_type="multi_choice"
                input_id="analyses"
                :choices="analyses_all"
                v-model="form.analyses"
            >
                Scientific Analysis
            </EthInput>

            <EthInput
                input_type="textarea"
                input_id="analyses_comment"
                v-model="form.analyses_comment"
                >Comments on Analysis</EthInput
            >

            <button @click.prevent="submit">Store All Changes</button>
        </fieldset>

        <fieldset v-if="activetab == 'provenance'" class="container">
            <legend>Provenance</legend>
            <EthInput
                input_type="single_choice"
                input_id="first_procurement"
                :choices="first_procurements"
                v-model="form.first_procurement_id"
            >
                Mode of First Procurement
            </EthInput>

            <EthInput
                input_type="single_choice"
                input_id="ancient_provenance"
                :choices="ancient_provenances"
                v-model="form.ancient_provenance_id"
            >
                Ancient Provenance
            </EthInput>

            <EthInput
                input_type="single_choice"
                input_id="ancient_provenance_certainty"
                :choices="ancient_provenance_certainties"
                v-model="form.ancient_provenance_certainty_id"
            >
                Certainty of Ancient Provenance
            </EthInput>

            <EthInput
                input_type="textarea"
                input_id="ancient_provenance_comment"
                v-model="form.ancient_provenance_comment"
                >Comments on Ancient Provenance
            >
            </EthInput>
            
            <EthInput
                input_type="single_choice"
                input_id="modern_collection"
                :choices="modern_collections"
                v-model="form.modern_collection_id"
            >
                Modern Collection
            </EthInput>

            <EthInput
                input_type="single_choice"
                input_id="legal_classification"
                :choices="legal_classifications"
                v-model="form.legal_classification_id"
            >
                Legal Classification
            </EthInput>

            <EthInput
                input_type="textarea"
                input_id="legal_classification_explanation"
                v-model="form.legal_classification_explanation"
                >Comments on Legal Classification</EthInput
            >

            <EthInput
                input_type="multi_choice"
                input_id="purchases"
                :choices="purchases_all"
                v-model="form.purchases"
            >
                Purchases
            </EthInput>
            <button @click.prevent="submit">Store All Changes</button>
        </fieldset>

        <fieldset v-if="activetab == 'images'" class="container">
            <legend>Images</legend>
 
            <EthInput
                input_type="textarea"
                input_id="images_info"
                v-model="form.images_info"
                >Image Information</EthInput
            >

            <label for="images">Upload Images</label>
            <input
                type="file"
                @change="addimages($event.target.files)"
                multiple
                ref="loadImages"
            />
            <button @click.prevent="submit">Store All Changes</button>

            <div class="flex-container">
                <div
                    class="box"
                    v-for="(image, index) in form.images"
                    :key="image.id"
                >
                    <a :href="'/storage/' + image.filename" target="_blank">
                        <img :src="'/storage/' + image.filename" height="400" />
                    </a>
                    <button @click.prevent="delimage(image)">Delete</button>
                    <label :for="'image_description_' + index">Description: </label>
                    <textarea :id="'image_description_' + index" v-model="image.description" />
                    
                    <EthInput
                        input_type="text"
                        input_id="image_source"
                        v-model="image.source"
                    >
                    Source
                    </EthInput>

                    <EthInput
                        input_type="single_choice"
                        input_id="license"
                        :choices="licenses"
                        v-model="image.license_id"
                    >
                    License:
                    </EthInput>

                </div>
            </div>
        </fieldset>
    </form>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { useRemember } from "@inertiajs/inertia-vue3";


import EthInput from "../Components/EthInput.vue";
import EthRatio from "../Components/EthRatio.vue";

const props = defineProps({
    analyses: Object,
    analyses_all: Object,
    ancient_provenance: Object,
    ancient_provenances: Array,
    ancient_provenance_certainty: Object,
    ancient_provenance_certainties: Array,
    cover: Object,
    covers: Array,
    dating_certainty: Object,
    dating_certainties: Array,
    dating_method: Object,
    dating_methods: Array,
    decorations: Object,
    decorations_all: Object,
    document: Object,
    genres: Array,
    genres_all: Array,
    first_procurement: Object,
    first_procurements: Array,
    images: Array,
    ink: Object,
    inks: Array,
    languages: Array,
    languages_all: Array,
    legal_classification: Object,
    legal_classifications: Array,
    licenses: Array,
    material: Object,
    materials: Array,
    modern_collection: Object,
    modern_collections: Array,
    newimages: Array,
    pagination: Object,
    paginations: Array,
    paratexts: Array,
    paratexts_all: Array,
    purchases: Array,
    purchases_all: Array,
    quire_signature: Array,
    quire_signatures: Array,
    quire_structure: Object,
    quire_structures: Array,
    scripts: Array,
    scripts_all: Array,
    storage_condition: Object,
    storage_conditions: Array,
    tags: Array,
    tags_all: Array,
});


const form = useForm({
    id: props.document.id,
    standard_name: props.document.standard_name,
    other_names: props.document.other_names,
    publication: props.document.publication,
    current_shelfmarks: props.document.current_shelfmarks,
    trismegistos_id: props.document.trismegistos_id,
    title: props.document.title,
    genres: props.genres,
    first_procurement_id: props.document.first_procurement_id,
    ancient_author: props.document.ancient_author,
    tags: props.tags,
    content_description: props.document.content_description,
    start_year: props.document.start_year,
    end_year: props.document.end_year,
    languages: props.languages,
    dating_method_id: props.document.dating_method_id,
    dating_certainty_id: props.document.dating_certainty_id,
    dating_comment: props.document.dating_comment,
    bibliography: props.document.bibliography,
    internal_comment: props.document.internal_comment,
    general_comment: props.document.general_comment,
    material_id: props.document.material_id,
    full_page_width: props.document.full_page_width,
    full_page_height: props.document.full_page_height,
    upper_margin: props.document.upper_margin,
    lower_margin: props.document.lower_margin,
    inner_margin: props.document.inner_margin,
    outer_margin: props.document.outer_margin,
    full_text_block_width: props.document.full_text_block_width,
    full_text_block_height: props.document.full_text_block_height,
    measurement_comment: props.document.measurement_comment,
    scripts: props.scripts,
    hand_number: props.document.hand_number,
    script_description: props.document.script_description,
    paratexts: props.paratexts,
    decorations: props.decorations,
    pagination_id: props.document.pagination_id,
    cover_id: props.document.cover_id,
    ink_id: props.document.ink_id,
    quire_signature_id: props.document.quire_signature_id,
    quire_structure_id: props.document.quire_structure_id,
    quire_number: props.document.quire_number,
    bifolia: props.document.bifolia,
    quire_comment: props.document.quire_comment,
    binding_description: props.document.binding_description,
    storage_condition_id: props.document.storage_condition_id,
    conservation_history: props.document.conservation_history,
    analyses: props.analyses,
    analyses_comment: props.document.analyses_comment,
    ancient_provenance_id: props.document.ancient_provenance_id,
    ancient_provenance_certainty_id: props.document.ancient_provenance_certainty_id,
    ancient_provenance_comment: props.document.ancient_provenance_comment,
    modern_collection_id: props.document.modern_collection_id,
    legal_classification_id: props.document.legal_classification_id,
    legal_classification_explanation: props.document.legal_classification_explanation,
    purchases: props.purchases,
    images_info: props.document.images_info,
    images: props.images,
});


const loadImages = ref(null);

let activetab = ref("general");

function delimage(image) {
    let image_id = image.id
    if (confirm('Do you want to delete this image?')) {
        Inertia.post("/delimage/" + image_id, null, {
                                preserveState: true,
                                preserveScroll: true,
                                resetOnSuccess: false,
                                onSuccess: () => {
                                    let index = form.images.findIndex(function(o){
                                        return o.id === image_id;
                                    })
                                    if (index !== -1) {form.images.splice(index,1)};
                                }
        })
    }
}

function addimages(imagefiles) {
   // Inertia.remember(form.value, 'form');

    Inertia.post("/addimages", {images: imagefiles,
                                document_id: props.document.id,},
                                { preserveState: true,
                                preserveScroll: true,
                                resetOnSuccess: false,
                                onSuccess: () => {
                                    props.images.forEach( (image, index) => {
                                        if (!form.images.findIndex(x => x.id == image.id)) {
                                        form.images.push(image)
                                        }
                                    } ) 
                                    if(form.images.length == 0) {
                                        props.images.forEach((image, index) => {
                                            form.images.push(image)
                                        } )
                                    }
                                    
                                    }}
                )

    this.loadImages.value=null;
//    this.form.images = this.props.images;
}

function submit() {
    form.post("/codex_update/" + props.document.id);
}
</script>

<style>
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

.tab button:hover {
    background-color: #ddd;
}

.tab button.active {
    background-color: #ccc;
}

.container {
    display: flex;
    background-color: #bbb;
    margin: 10px;
    padding: 20px;
    font-family: sans-serif;
    font-size: 14px;
    flex-direction: column;
    border-radius: 10px;
}

.flex-container {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

.box {
    padding: 10px;
    background-color: lightslategrey;
    margin: 5px;
    display: flex;
    flex-direction: column;
}

legend {
    border-radius: 10px;
    padding: 15px;
    background-color: #333;
    color: #fff;
    font-size: 16px;
}
label {
    padding-top: 15px;
    padding-right: 15px;
}
</style>
