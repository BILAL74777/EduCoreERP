export function useNavigation() {
    const goBack = () => { 
        window.history.back();
    };

    return {
        goBack,
    };
}