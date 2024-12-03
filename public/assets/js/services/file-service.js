
class FileService {
    static #file = null;

    constructor(file) {
        FileService.#file = file;
    }

    getSize(file = FileService.#file) {
        try {
            if (!file || !file.size) {
                return '-';
            }

            const units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            let fileSize = file.size;

            if (isNaN(fileSize) || fileSize < 0) {
                return '-';
            }

            let i = 0;
            while (fileSize > 900 && i < units.length - 1) {
                fileSize /= 1024;
                i++;
            }

            return `${Math.round(fileSize * 100) / 100} ${units[i]}`;
        } catch (error) {
            return '-';
        }
    }

    getName(file = FileService.#file){
        return file.name;
    }

    getType(file = FileService.#file){
        return file.type;
    }

    getExtension(file=FileService.#file){

        const name = this.getName(file);
        if(name.indexOf('.') > -1){
            return name.substring(name.lastIndexOf('.') + 1).toUpperCase();
        }
        const type = this.getType(file);
        if(type == ""){
            return "-";
        }
        return type.substring(type.lastIndexOf('/') + 1).toUpperCase();

    }



}
