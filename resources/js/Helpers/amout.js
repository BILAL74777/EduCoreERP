export const formatAmount = (value) => {
 
    if (value === null || value === undefined || value === "") return "-";

    return Number(value || 0).toLocaleString("en-PK");
};