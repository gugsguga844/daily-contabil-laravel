export function useFormatters() {
    const formatDate = (isoString, timeZone = 'America/Sao_Paulo') => {
        if(!isoString) return '';
        const d = new Date(isoString);
        if(isNaN(d)) return '';
        return new Intl.DateTimeFormat('pt-BR', {
            timeZone,
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        }).format(d);
    }

    const formatBytes = (bytes) => {
        if(bytes === null || bytes === undefined) return '';
        const b = Number(bytes);
        if(!isFinite(b)) return '';
        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(Math.max(b, 1)) / Math.log(1024));
        const value = b / Math.pow(1024, i);
        return `${value.toFixed(value < 10 && i > 0 ? 1 : 0)} ${units[i]}`;
    }

    return {
        formatDate,
        formatBytes,
    }
}
