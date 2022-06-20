<script>

function getAllRecipes() {
  return http.get('api/recipes')
}

function newRecipe(params) {
  return http.post('/api/recipes', params)
}

function getAllIngredients() {
  return http.get('api/ingredients')
}

function findIngredientByName(search) {
  return http.get('api/ingredients?name=' + search)
}

function newIngredient(newIngredient) {
  return http.post('api/ingredients', newIngredient )
}

import http from '../utils/http'

export default {
  name :'ServerFile',
  getAllRecipes,
  getAllIngredients,
  newRecipe,
  findIngredientByName,
  newIngredient,

}
</script>
