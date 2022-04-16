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

import http from '../utils/http'

export default {
  name :'ServerFile',
  getAllRecipes,
  getAllIngredients,
  newRecipe,
}
</script>
