<template>
  <div class="home">
    <h1>Carnet de recette</h1>
    <div class="newRecipes" @click="showForm = !showForm">+</div>
    <form v-show="showForm">
      <input type="text" placeholder="Nom de la recette">
      <div class="ingredientsFields" v-for="ingredient, index in ingredients" :key="index">
        <input type="text" placeholder="Ingredient" v-model="ingredient.name">
        <input type="number" placeholder="Quantiter" v-model="ingredient.quantity">
        <select v-model="ingredient.unity">
          <option value="gr">gr</option>
          <option value="pce">pce</option>
        </select> 
      </div>
      <div class="newRecipes" @click="addFields">+</div>
      <textarea name="method" v-model="method"/>
    </form>
     <button class="newRecipes" @click="newRecipe">Soumettre le formulaire</button>
  </div>
</template>

<script>
export default {
  name: 'HomeView',
  components: {
  },
  data() {
    return {
      showForm: false,
      name: '',
      method: '',
      ingredients: [{
        name: '',
        quantity: Number,
        unity: '',
      }],
    }
  },
  methods: {
    addFields() {
      this.ingredients.push({
        name: '',
        quantity: Number,
        unity: ''
      })
    },
    newRecipe() {
      console.log(this.ingredients)
    }
  }
}
</script>

<style lang="scss">
  .newRecipes {
    font-size: 2em;
    border-radius: 50%;
    background-color: cornflowerblue;
    color: white;
    border: 2px solid cornflowerblue;
    width: 50px;
    height: 50px;
    cursor: pointer;
    line-height: 52px;
    margin: 10px auto;
  }
</style>