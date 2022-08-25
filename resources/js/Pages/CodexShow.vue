<template>
    <h1 class="showcodexheader">General Information</h1>

    <div class="showcodexcontainer">

        <div class="showfield">
            <p>Standard Name:</p>
            <p>{{ document.standard_name }}</p>
        </div>

        <div class="showfield">
            <p>Trismegistos ID</p> 
            <p>{{ document.trismegistos_id }}</p>
        </div>

        <div class="showfield">
            <p>Other Names:</p>
            <p>{{ document.other_names }}</p>
        </div>

        <div class="showfield">
                <p>Current Shelfmarks:</p>
                <p>{{ document.current_shelfmarks }}</p>
        </div>

        <div class="showfield">
                <p>Publication:</p>
                <p>{{ document.publication }}</p>
        </div>

        <div class="showfield">
                <p>Title:</p>
                <p>{{ document.title }}</p>
        </div>

        <div class="showfield">
                <p>Author:</p>
                <p>{{ document.ancient_author }}</p>
        </div>

        <div class="showfield">
                <p>Genre(s):</p>
                <p>
                    <span
                        v-for="(genre, index) in genres"
                        :key="genre.id"
                        >{{ genre.name }}
                        <span v-if="index + 1 != genres.length">, </span>
                    </span>
                </p>
        </div>

        <div class="showfield">
                <p>Content Tags:</p>
                <p>
                        <span v-for="(tag, index) in tags" :key="tag.id">
                            {{ tag.name }}
                        <span v-if="index + 1 != tags.length">, </span>
                    </span>
                </p>
        </div>

        <div class="showfield">
                <p>Content Description:</p>
                <p>{{ document.content_description }}</p>
        </div>

        <div class="showfield">
                <p>Language(s):</p>
                <p>
                    <span
                        v-for="(language, index) in languages"
                        :key="language.id"
                        >{{ language.name }}
                        <span v-if="index + 1 != languages.length">, </span>
                    </span>
                </p>
        </div>

        <div class="showfield">
                <p>Date:</p>
                <p>
                    AD {{ document.start_year }} – {{ document.end_year }}
                </p>

        </div>

        <div class="showfield">
                <p>
                    Dating Method:
                    {{ dating_method[0] == null ? "" : dating_method[0].name }}
                </p>
        </div>

        <div class="showfield">
                <p>
                    Certainty of Date:
                    {{
                        dating_certainty[0] == null
                            ? ""
                            : dating_certainty[0].name
                    }}
                </p>
        </div>

        <div class="showfield">
                <p>Comments on Date:</p>
                <p>{{ document.dating_comment }}</p>
        </div>

        <div class="showfield">
                <p>Bibliography:</p>
                <p>{{ document.bibliography }}</p>
        </div>

        <div class="showfield">
                <p>General Comments:</p>
                <p>{{ document.general_comment }}</p>
        </div>

        <div v-if="edit" class="showfield">
                <p>Internal Comments:</p>
                <p>{{ document.internal_comment }}</p>
        </div>
    </div>

    <h1 class="showcodexheader">Codicology</h1>

    <div class="showcodexcontainer">
        <div class="showfield">
                <p>Material:</p>
                <p>
                    {{ material[0] == null ? "" : material[0].name }}
                </p>
        </div>

        <div class="showfield">
                <p>Full Page Width:</p>
                <p>{{ document.full_page_width }}</p>
        </div>

        <div class="showfield">
                <p>Full Page Height:</p>
                <p>{{ document.full_page_height }}</p>
        </div>

        <div class="showfield">
                <p>
                    <EthRatio
                        :dividend="document.full_page_width"
                        :divisor="document.full_page_height"
                        >Full Width/Height Ratio</EthRatio
                    >
                </p>
        </div>

        <div class="showfield">
                <p>Upper Margin:</p>
                <p>{{ document.upper_margin }}</p>
        </div>

        <div class="showfield">
                <p>Lower Margin:</p>
                <p>{{ document.lower_margin }}</p>
        </div>

        <div class="showfield">
                <p>
                    <EthRatio
                        :dividend="document.upper_margin"
                        :divisor="document.lower_margin"
                        >Upper/Lower Margin Ratio</EthRatio
                    >
                </p>
        </div>

        <div class="showfield">
                <p>Inner Margin:</p>
                <p>{{ document.inner_margin }}</p>
        </div>

        <div class="showfield">
                <p>Outer Margin:</p>
                <p>{{ document.outer_margin }}</p>
        </div>

        <div class="showfield">
                <p>
                    <EthRatio
                        :dividend="document.inner_margin"
                        :divisor="document.outer_margin"
                        >Inner/Outer Margin Ratio</EthRatio
                    >
                </p>
        </div>

        <div class="showfield">
                <p>Full Text Block Width:</p>
                <p>{{ document.full_text_block_width }}</p>
        </div>

        <div class="showfield">
                <p>Full Text Block Height:</p>
                <p>{{ document.full_text_block_height }}</p>
        </div>

        <div class="showfield">
                <p>
                    <EthRatio
                        :dividend="document.full_text_block_width"
                        :divisor="document.full_text_block_height"
                        >Full Text Block Width/Height Ratio</EthRatio
                    >
                </p>
        </div>

        <div class="showfield">
                <p>Comments on Measurements:</p>
                <p>{{ document.measurement_comment }}</p>
        </div>

        <div class="showfield">
                <p>Script(s):</p>
                <p>
                    <span v-for="(script, index) in scripts" :key="script.id"
                        >{{ script.name }}
                        <span v-if="index + 1 != scripts.length">, </span>
                    </span>
                </p>
        </div>

        <div class="showfield">
                <p>Number of Hands:</p>
                <p> {{ document.hand_number }}</p>
        </div>

        <div class="showfield">
                <p>Script Description:</p>
                <p>{{ document.script_description }}</p>
        </div>

        <div class="showfield">
                <p>Paratexts:</p>
                <p>
                    <span
                        v-for="(paratext, index) in paratexts"
                        :key="paratext.id"
                        >{{ paratext.name }}
                        <span v-if="index + 1 != paratexts.length">, </span>
                    </span>
                </p>
        </div>

        <div class="showfield">
                <p>Decorations:</p>
                <p>
                    <span
                        v-for="(decoration, index) in decorations"
                        :key="decoration.id"
                        >{{ decoration.name }}
                        <span v-if="index + 1 != decorations.length">, </span>
                    </span>
                </p>
        </div>

        <div class="showfield">
                <p>Quire Structure:</p>
                <p>
                    {{
                        quire_structure[0] == null
                            ? ""
                            : quire_structure[0].name
                    }}
                </p>
        </div>

        <div class="showfield">
                <p>
                    Quire Signatures:
                    {{
                        quire_signature[0] == null
                            ? ""
                            : quire_signature[0].name
                    }}
                </p>
        </div>

        <div class="showfield">
                <p>Pagination:</p>
                <p>    {{ pagination[0] == null ? "" : pagination[0].name }}</p>
        </div>

        <div class="showfield">
                <p>Cover:</p>
                <p> {{ cover[0] == null ? "" : cover[0].name }}</p>
        </div>

        <div class="showfield">
                <p>Ink:</p>
                <p> {{ ink[0] == null ? "" : ink[0].name }}</p>
        </div>

        <div
            v-if="quire_structure[0] == null
                    ? false
                    : quire_structure[0].id == 1
                    ? true
                    : false
                "
            class="showfield"
        >
            <p>Number of Bifolia in the Quire:</p>
            <p>{{ JSON.parse(document.bifolia)[0] }}</p>
        </div>

        <div 
            v-if="quire_structure[0] == null
                    ? false
                    : quire_structure[0].id == 2
                    ? true
                    : false
                "
            class="showfield">
            <p>Number of Quires:</p>
            <p>{{ document.quire_number }}</p>
        </div>

        <div v-if="quire_structure[0] == null
                    ? false
                    : quire_structure[0].id == 3
                    ? true
                    : false
                "
                class="showfield"
        >
            <p>Number of Bifolia in each Quire:</p>
            <p>
                <span
                    v-for="(bifolium, index) in JSON.parse(
                        document.bifolia
                    )"
                    :key="bifolium.id"
                    >{{ index + 1 }}: {{ bifolium }}
                    <span
                        v-if="
                            index + 1 != JSON.parse(document.bifolia).length
                        "
                        >,
                    </span>
                </span>
            </p>
        </div>

        <div 
            v-if="
                quire_structure[0] == null
                    ? false
                    : quire_structure[0].id == 3
                    ? true
                    : false
                "
                class="showfield"
        >
            <p>Number of Quires:</p>
            <p>{{ document.quire_number }}</p>
        </div>

        <div 
            v-if="quire_structure[0] == null
                ? false
                : quire_structure[0].id == 3
                ? true
                : false
                "
            class="showfield"
        >
            <p>Number of Bifolia per Quire:</p>
            <p>{{ JSON.parse(document.bifolia)[0] }}</p>
        </div>

        <div class="showfield">
                <p>Comments on Quire(s):</p>
                <p>{{ document.quire_comment }}</p>
        </div>

        <div class="showfield">
                <p>Binding Description:</p>
                <p>{{ document.binding_description }}</p>
        </div>
    </div>

    <h1 class="showcodexheader">Conservation and Analysis</h1>
    
    <div class="showcodexcontainer">
         
        <div class="showfield">
            <p>Storage Condition:</p>
            <p>
                {{
                    storage_condition[0] == null
                        ? ""
                        : storage_condition[0].name
                }}
            </p>
        </div>

        <div class="showfield">
            <p>Conservation History:</p>
            <p>{{ document.conservation_history }}</p></div>

        <div class="showfield">
            <p>Analyses:</p>
            <p>
                <span
                    v-for="(analysis, index) in analyses"
                    :key="analysis.id"
                >
                    {{ analysis.name }}
                    <span v-if="index + 1 != analyses.length">, </span>
                </span>
            </p>
        </div>

        <div class="showfield">
            <p>Comments on Analyses:</p>
            <p>{{ document.analyses_comment }}</p>
        </div>
    </div>

    <h1 class="showcodexheader">Provenance</h1>
 
    <div class="showcodexcontainer">

        <div class="showfield">
            <p>Mode of First Procurement</p>
                <p>
                    {{ 
                        first_procurement[0] == null
                        ? ""
                        : first_procurement[0].name 
                    }}
                </p>
        </div>

        <div class="showfield">
        <p>Ancient Provenance:</p>
                <p>
                    {{
                        ancient_provenance[0] == null
                            ? ""
                            : ancient_provenance[0].name
                    }}
                </p>
        </div>

        <div class="showfield">
            <p>
                Certainty:
                {{
                    ancient_provenance_certainty[0] == null
                        ? ""
                        : ancient_provenance_certainty[0].name
                }}
            </p>
        </div>

        <div class="showfield">
            <p>
                    Certainty:
                    {{
                        ancient_provenance_certainty[0] == null
                            ? ""
                            : ancient_provenance_certainty[0].name
                    }}
                </p> 
        </div>

        <div class="showfield">
            <p>Comment on Ancient Provenance:</p>
            <p>
                {{ document.ancient_provenance_comment }}
            </p>
        </div>

        <div class="showfield">
            <p>Modern Collection:</p>
                <p>
                    {{
                        modern_collection[0] == null
                            ? ""
                            : modern_collection[0].name
                    }}
                </p>
        </div>

        <div class="showfield">
            <p>
                    Legal Classification:
                    {{
                        legal_classification[0] == null
                            ? ""
                            : legal_classification[0].name
                    }}
                </p>
        </div>

        <div class="showfield">
        <p>Comments on Legal Classification</p>
                <p>
                    {{ document.legal_classification_explanation }}
                </p>
        </div>

        <div class="showfield">
            <p>Purchases:</p>
            
            <div v-for="purchase in purchases" :key="purchase.id">
                <p>{{ purchase.date }}</p>
                <p>{{ purchase.name }}</p>
                <p>{{ purchase.description }}</p>
            </div> 
        </div>
    </div>

    <h1 class="showcodexheader">Images</h1>
 
    <div class="showcodexcontainer">
               <div class="box" v-for="(image, index) in images" :key="image.id">
                    <div style="float: right"
                        ><button @click="show_desc[index] = !show_desc[index]">
                            Details
                        </button>
                    </div>
                <div v-if="show_desc[index]">
                    <p><b>Details:</b></p>
                    <p v-if="image.description != null">
                        <b>Description:</b><br/>{{ image.description }}
                    </p>
                    <p v-if="image.source != null">
                        <b>Source:</b><br/>{{ image.source }}
                    </p>
                    <p v-if="image.license_id != null">
                        <b>License:</b><br/>{{ image.license.name }}
                    </p>
                </div>

                <a :href="'/storage/' + image.filename" target="_blank">
                    <img :src="'/storage/' + image.filename" height="400" />
                </a>
            </div>    
  

    </div>


</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
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
    first_procurement: Object,
    first_procurements: Array,
    genres: Array,
    genres_all: Array,
    images: Array,
    ink: Object,
    inks: Array,
    languages: Object,
    languages_all: Object,
    legal_classification: Object,
    legal_classifications: Array,
    material: Object,
    materials: Array,
    modern_collection: Object,
    modern_collections: Array,
    pagination: Object,
    paginations: Array,
    paratexts: Object,
    paratexts_all: Object,
    purchases: Object,
    purchases_all: Object,
    quire_signature: Object,
    quire_signatures: Array,
    quire_structure: Object,
    quire_structures: Array,
    scripts: Object,
    scripts_all: Object,
    storage_condition: Object,
    storage_conditions: Array,
    tags: Object,
    auth: Object,
});

const show_desc = reactive([]);

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);

</script>

<style>
.showcodexcontainer {
    display: grid;
    grid-template-columns: auto auto auto;
    background-color: #eee;
    margin-top: 4px;
    padding: 20px;
    font-family: sans-serif;
    font-size: 14px;
    width: 100%;
}

.showcodexheader {
    font-size: larger;
    font-weight: bold;
    padding: 1em 1em 0em 1em;

}

.showfield {
    display: flex;
    flex-direction:column;
    padding: 20px;
}

.showfield p:first-child {
    font-weight: bold;
    font-size: large;
}

.box {
    padding: 10px;
    background-color: lightslategrey;
    margin: 5px;
    display: flex;
    flex-direction: column;
}


.labelpadding {
    padding-top: 15px;
    padding-right: 15px;
}
</style>
