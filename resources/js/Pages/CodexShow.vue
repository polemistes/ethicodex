<template>
    <div class="container">
        <legend>General Information</legend>
        <table>
            <tr>
                <td>Standard Name:</td>
                <td colspan="4">{{ document.standard_name }}</td>
                <td>Trismegistos ID: {{ document.trismegistos_id }}</td>
            </tr>
            <tr>
                <td>Other Names:</td>
                <td colspan="5">{{ document.other_names }}</td>
            </tr>
            <tr>
                <td>Current Shelfmarks:</td>
                <td colspan="5">{{ document.current_shelfmarks }}</td>
            </tr>
            <tr>
                <td>Publication:</td>
                <td colspan="5">{{ document.publication }}</td>
            </tr>
            <tr>
                <td>Title:</td>
                <td colspan="5">{{ document.title }}</td>
            </tr>
            <tr>
                <td>Author:</td>
                <td colspan="5">{{ document.ancient_author }}</td>
            </tr>

            <tr>
                <td>Genre(s):</td>
                <td colspan="5">
                    <span
                        v-for="(genre, index) in genres"
                        :key="genre.id"
                        >{{ genre.name }}
                        <span v-if="index + 1 != genres.length">, </span>
                    </span>
                </td>
            </tr>

            <tr>
                <td>Content Tags:</td>
                    <td colspan=5>
                        <span v-for="(tag, index) in tags" :key="tag.id">
                            {{ tag.name }}
                        <span v-if="index + 1 != tags.length">, </span>
                    </span>
                </td>
            </tr>

            <tr>
                <td>Content Description:</td>
                <td colspan="5">{{ document.content_description }}</td>
            </tr>

            <tr>
                <td>Language(s):</td>
                <td colspan="5">
                    <span
                        v-for="(language, index) in languages"
                        :key="language.id"
                        >{{ language.name }}
                        <span v-if="index + 1 != languages.length">, </span>
                    </span>
                </td>
            </tr>

            <tr>
                <td>Date:</td>
                <td colspan="1">
                    AD {{ document.start_year }} – {{ document.end_year }}
                </td>
                <td colspan="3">
                    Dating Method:
                    {{ dating_method[0] == null ? "" : dating_method[0].name }}
                </td>
                <td colspan="1">
                    Certainty of Date:
                    {{
                        dating_certainty[0] == null
                            ? ""
                            : dating_certainty[0].name
                    }}
                </td>
            </tr>

            <tr>
                <td>Comments on Date:</td>
                <td colspan="5">{{ document.dating_comment }}</td>
            </tr>
            <tr>
                <td>Bibliography:</td>
                <td colspan="5">{{ document.bibliography }}</td>
            </tr>
            <tr>
                <td>General Comments:</td>
                <td colspan="5">{{ document.general_comment }}</td>
            </tr>
            <tr v-if="edit">
                <td>Internal Comments:</td>
                <td colspan="5">{{ document.internal_comment }}</td>
            </tr>
        </table>
    </div>
    <div class="container">
        <legend>Codicology</legend>
        <table>
            <tr>
                <td>Material:</td>
                <td colspan="1">
                    {{ material[0] == null ? "" : material[0].name }}
                </td>
                <td colspan="4"></td>
            </tr>

            <tr>
                <td>Full Page Width:</td>
                <td colspan="2">{{ document.full_page_width }}</td>
                <td colspan="1">Full Page Height:</td>
                <td>{{ document.full_page_height }}</td>
                <td colspan="1">
                    <EthRatio
                        :dividend="document.full_page_width"
                        :divisor="document.full_page_height"
                        >Full Width/Height Ratio</EthRatio
                    >
                </td>
            </tr>

            <tr>
                <td>Upper Margin:</td>
                <td colspan="2">{{ document.upper_margin }}</td>
                <td colspan="1">Lower Margin:</td>
                <td>{{ document.lower_margin }}</td>
                <td colspan="1">
                    <EthRatio
                        :dividend="document.upper_margin"
                        :divisor="document.lower_margin"
                        >Upper/Lower Margin Ratio</EthRatio
                    >
                </td>
            </tr>

            <tr>
                <td>Inner Margin:</td>
                <td colspan="2">{{ document.inner_margin }}</td>
                <td colspan="1">Outer Margin:</td>
                <td>{{ document.outer_margin }}</td>
                <td colspan="1">
                    <EthRatio
                        :dividend="document.inner_margin"
                        :divisor="document.outer_margin"
                        >Inner/Outer Margin Ratio</EthRatio
                    >
                </td>
            </tr>

            <tr>
                <td>Full Text Block Width:</td>
                <td colspan="2">{{ document.full_text_block_width }}</td>
                <td colspan="1">Full Text Block Height:</td>
                <td>{{ document.full_text_block_height }}</td>
                <td colspan="1">
                    <EthRatio
                        :dividend="document.full_text_block_width"
                        :divisor="document.full_text_block_height"
                        >Full Text Block Width/Height Ratio</EthRatio
                    >
                </td>
            </tr>

            <tr>
                <td>Comments on Measurements:</td>
                <td colspan="5">{{ document.measurement_comment }}</td>
            </tr>

            <tr>
                <td>Script(s):</td>
                <td colspan="4">
                    <span v-for="(script, index) in scripts" :key="script.id"
                        >{{ script.name }}
                        <span v-if="index + 1 != scripts.length">, </span>
                    </span>
                </td>
                <td>Number of Hands: {{ document.hand_number }}</td>
            </tr>

            <tr>
                <td>Script Description:</td>
                <td colspan="5">{{ document.script_description }}</td>
            </tr>

            <tr>
                <td>Paratexts:</td>
                <td colspan="5">
                    <span
                        v-for="(paratext, index) in paratexts"
                        :key="paratext.id"
                        >{{ paratext.name }}
                        <span v-if="index + 1 != paratexts.length">, </span>
                    </span>
                </td>
            </tr>

            <tr>
                <td>Decorations:</td>
                <td colspan="5">
                    <span
                        v-for="(decoration, index) in decorations"
                        :key="decoration.id"
                        >{{ decoration.name }}
                        <span v-if="index + 1 != decorations.length">, </span>
                    </span>
                </td>
            </tr>

            <tr>
                <td>Quire Structure:</td>
                <td>
                    {{
                        quire_structure[0] == null
                            ? ""
                            : quire_structure[0].name
                    }}
                </td>
                <td>
                    Quire Signatures:
                    {{
                        quire_signature[0] == null
                            ? ""
                            : quire_signature[0].name
                    }}
                </td>
                <td>
                    Pagination:
                    {{ pagination[0] == null ? "" : pagination[0].name }}
                </td>
                <td>Cover: {{ cover[0] == null ? "" : cover[0].name }}</td>
                <td>Ink: {{ ink[0] == null ? "" : ink[0].name }}</td>
            </tr>

            <tr
                v-if="
                    quire_structure[0] == null
                        ? false
                        : quire_structure[0].id == 1
                        ? true
                        : false
                "
            >
                <td>Number of Bifolia in the Quire:</td>
                <td colspan="5">{{ JSON.parse(document.bifolia)[0] }}</td>
            </tr>

            <tr
                v-if="
                    quire_structure[0] == null
                        ? false
                        : quire_structure[0].id == 2
                        ? true
                        : false
                "
            >
                <td>Number of Quires:</td>
                <td colspan="2">{{ document.quire_number }}</td>
                <td>Number of Bifolia in each Quire:</td>
                <td colspan="5">
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
                </td>
            </tr>

            <tr
                v-if="
                    quire_structure[0] == null
                        ? false
                        : quire_structure[0].id == 3
                        ? true
                        : false
                "
            >
                <td>Number of Quires:</td>
                <td colspan="2">{{ document.quire_number }}</td>
                <td>Number of Bifolia per Quire:</td>
                <td colspan="2">{{ JSON.parse(document.bifolia)[0] }}</td>
            </tr>

            <tr>
                <td>Comments on Quire(s):</td>
                <td colspan="5">{{ document.quire_comment }}</td>
            </tr>
            <tr>
                <td>Binding Description:</td>
                <td colspan="5">{{ document.binding_description }}</td>
            </tr>
        </table>
    </div>
    <div class="container">
        <legend>Conservation and Analysis</legend>
        <table>
            <tr>
                <td>Storage Condition:</td>
                <td colspan="5">
                    {{
                        storage_condition[0] == null
                            ? ""
                            : storage_condition[0].name
                    }}
                </td>
            </tr>
            <tr>
                <td>Conservation History:</td>
                <td colspan="5">{{ document.conservation_history }}</td>
            </tr>

            <tr>
                <td>Analyses:</td>
                <td colspan="5">
                    <span
                        v-for="(analysis, index) in analyses"
                        :key="analysis.id"
                        >{{ analysis.name }}
                        <span v-if="index + 1 != analyses.length">, </span>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Comments on Analyses:</td>
                <td colspan="5">{{ document.analyses_comment }}</td>
            </tr>
        </table>
    </div>
    <div class="container">
        <legend>Provenance</legend>
        <table>
            <tr>
                <td>Mode of First Procurement</td>
                <td colspan="6">
                    {{ 
                        first_procurement[0] == null
                        ? ""
                        : first_procurement[0].name 
                    }}
                </td>
            </tr>

            <tr>
                <td>Ancient Provenance:</td>
                <td colspan="5">
                    {{
                        ancient_provenance[0] == null
                            ? ""
                            : ancient_provenance[0].name
                    }}
                </td>
                <td>
                    Certainty:
                    {{
                        ancient_provenance_certainty[0] == null
                            ? ""
                            : ancient_provenance_certainty[0].name
                    }}
                </td>
            </tr>
            
            <tr>
                <td>Comment on Ancient Provenance:</td>
                <td colspan="6">
                    {{ document.ancient_provenance_comment }}
                </td>
            </tr>

            <tr>
                <td>Modern Collection:</td>
                <td colspan="5">
                    {{
                        modern_collection[0] == null
                            ? ""
                            : modern_collection[0].name
                    }}
                </td>
                <td>
                    Legal Classification:
                    {{
                        legal_classification[0] == null
                            ? ""
                            : legal_classification[0].name
                    }}
                </td>
            </tr>

            <tr>
                <td>Comments on Legal Classification</td>
                <td colspan="6">
                    {{ document.legal_classification_explanation }}
                </td>
            </tr>

            <tr>
                <td colspan="7">Purchases:</td>
            </tr>
            <tr v-for="purchase in purchases" :key="purchase.id">
                <td>{{ purchase.date }}</td>
                <td colspan="3">{{ purchase.name }}</td>
                <td colspan="3">{{ purchase.description }}</td>
            </tr>
        </table>
    </div>

    <div class="container">
        <legend>Images</legend>
        <div class="flex-container">
            <div class="box" v-for="(image, index) in images" :key="image.id">
                    <div style="float: right"
                        ><button @click="show_desc[index] = !show_desc[index]">
                            Details
                        </button>
                    </div>
                <p>Show: {{ show_desc[index] }}</p>
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
.container {
    display: flex;
    flex-direction: column;
    background-color: #bbb;
    margin: 10px;
    padding: 20px;
    font-family: sans-serif;
    font-size: 14px;
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
