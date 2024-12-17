/**
 * Seleciona a role e preenche as suas permissões
 * @author Bernardo Issenguel <beissenguel12@gmail.com>
 * @param {number} role_id identificador do papel
 * @returns {null}
 */
function getPermissionsByRoleId(role_id) {
    $.get(base_url + '/ajax/permissions/' + role_id, (permissions, status) => {
        if (status == 'success') {
            options = '';
            permissions.forEach((permission) => {
                options += `<option value="${permission.id}">${permission.nome}</option>`
            });
            $("#funcionalidade").html(options);
        } else {
            toastr.warning('Não encontro os perfis, por favor contacte o administrador', 'Erro');
        }
    });
}

$(function () {
    var role_id = $("#perfil").val();
    getPermissionsByRoleId(role_id);
    $("#perfil").change(() => {
        getPermissionsByRoleId($("#perfil").val());
    })
});
