import db from './db'

export const getCitiesWithGamesInfos = async () => await db.cities.toArray()

