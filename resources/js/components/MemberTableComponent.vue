<template>
    <table class="table table-hover" id='members-table'>
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="m in members">
                <td>{{ m.lastname }}</td>
                <td>{{ m.firstname }}</td>
                <td><a :href="m.edit_url"><i class="fas fa-pencil-alt"></i></a></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['members']
    }
</script>