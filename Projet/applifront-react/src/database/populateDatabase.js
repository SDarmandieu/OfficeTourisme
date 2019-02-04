import db from "./db";

/**
 * fill DB with the API created from SQL Laravel Backend
 */
export const populate = async () => {
    let response = await fetch(`${process.env.REACT_APP_API_URL}/database`)
    let data = await response.json()

    db.on("ready", function () {
        Object.entries(data).forEach(([tableName, tableDatas]) => {
            tableDatas.map(current => db.table(tableName).put(current))
        })
    });
    db.open();

    //
    // const getGame = async () => {
    //     let game = await db.games.where('id').equals(1).toArray();
    //     console.log(game)
    //
    //     let test = await db.points.where('id').anyOf(game[0]['points']).toArray();
    //     console.log(test)
    // }
    //
    // getGame()
    // getGame().then(data => getPoints(data))


    /*Object.entries(data).forEach(([tableName, tableDatas]) => {
        tableDatas.map(current => db.table(tableName)
            .add(current)
            .then(id => {
                const newpoi = [...this.state.markersDexie, Object.assign({}, current, {id})]
                this.setState({markersDexie: newpoi})
                console.log("marker Dexie", this.state.markersDexie)
            }))
    })*/
    // })
}