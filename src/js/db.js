import Dexie from 'dexie';

export const db = new Dexie('chefpilotDB');
db.version(3).stores({
    userItems: 'id, *user_id,name,category,people,item,*created_at,*updated_at',
    recentBookmarks: 'id, *user_id,name,description,ingredients,ingredientMatchScore,tag,difficulty,instructions,estimatedTimeMinutes,nutrition,extra,bookmarked,photos,*created_at,u*pdated_at',
    recipes: 'id, *user_id,name,description,ingredients,ingredientMatchScore,tag,difficulty,instructions,estimatedTimeMinutes,nutrition,extra,bookmarked,ulid,photos,*created_at,*updated_at',
    items:"id, name,category,image,image_type,*created_at,*updated_at"
});