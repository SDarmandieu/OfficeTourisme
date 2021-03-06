import db from "./db";

/**
 * fill DB with the API created from SQL Laravel Backend
 * @returns {Promise<void>}
 */
export const populate = async () => {
    let response = await fetch("https://1a3ae8f6.ngrok.io/api/database")
    let data = await response.json()

    db.on("ready", function () {
        Object.entries(data).forEach(([tableName, tableDatas]) => {
            tableDatas.map(current => db.table(tableName).put(current))
        })
    });
    db.open();
}