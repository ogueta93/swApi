<template>
<div>
    <b-container fluid class="container-content">
        <b-img v-bind="imgProps"></b-img>
        
        <b-row>
            <b-col>
                <h2>
                    SwaApi - <b-badge variant="primary">People</b-badge>
                </h2>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
               <tableComponent/>
            </b-col>
        </b-row>
    </b-container>
</div>
</template>

<script>
import tableComponent from '@/components/TableComponent.vue'

export default {
    name: 'mainView',
    components: {
        tableComponent
    },
    data: function() {
        return {
            imgProps: {
                src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Star_wars2.svg/1920px-Star_wars2.svg.png',
                center: true,
                fluid: true,
                class: "header-img"
            }
        }
    }
}
</script>

<style scope>
.header-img {
    height: 200px;
    margin: 0 0 50px 0;
}

.container-content {
    margin: 20px 0 0 0;
}

.container-content .row{
    margin: 0 0 20px 0;
}
</style>

