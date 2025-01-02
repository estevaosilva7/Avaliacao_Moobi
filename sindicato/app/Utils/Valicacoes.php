<?php
/**
 * Classe de validações.
 * Contém métodos estáticos para validar dados de entrada, como nome, CPF, RG, telefone, entre outros.
 * Cada método lança uma exceção caso os dados fornecidos não atendam aos critérios estabelecidos.
 *
 * @author Estevão carlosestevano@moobitech.com.br
 */
class Valicacoes {
	/**
	 * Valida o nome de usuário.
	 * O nome de usuário deve conter pelo menos uma letra e pode incluir apenas letras e números.
	 *
	 * @param string $sUsuario Nome de usuário a ser validado.
	 * @throws Exception Se o nome de usuário não atender aos critérios.
	 * @return void
	 */
	public static function validarUsuario(string $sUsuario): void {
		try {
			if (!preg_match('/^(?=.*[a-zA-Z])[a-zA-Z0-9]+$/', $sUsuario)) {
				throw new Exception("ALERTA! O nome de usuário deve conter pelo menos uma letra e pode incluir apenas letras e números.");
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Valida o nome.
	 * O nome deve conter apenas letras, incluindo caracteres acentuados e espaços.
	 *
	 * @param string $sNome Nome a ser validado.
	 * @throws Exception Se o nome não atender aos critérios.
	 * @return void
	 */
	public static function validarNome(string $sNome): void {
		try {
			if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $sNome)) {
				throw new Exception("ALERTA! Deve conter apenas letras.");
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Valida o CPF.
	 * O CPF deve conter exatamente 11 dígitos válidos e não pode ser um CPF com dígitos repetidos.
	 *
	 * @param string $sCpf CPF a ser validado.
	 * @throws Exception Se o CPF não atender aos critérios de validade.
	 * @return void
	 */
	public static function validarCPF(string $sCpf): void {
		try {
			$sCpf = preg_replace('/\D/', '', $sCpf);

			if (strlen($sCpf) !== 11 || preg_match('/^(\d)\1{10}$/', $sCpf)) {
				throw new Exception("O ALERTA! O CPF deve conter exatamente 11 dígitos válidos.");
			}
			for ($t = 9; $t < 11; $t++) {
				$d = 0;
				for ($c = 0; $c < $t; $c++) {
					$d += $sCpf[$c] * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($sCpf[$c] != $d) {
					throw new Exception("ALERTA! O CPF é inválido.");
				}
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Valida o RG.
	 * O RG deve conter exatamente 10 dígitos numéricos.
	 *
	 * @param string $sRg RG a ser validado.
	 * @throws Exception Se o RG não atender aos critérios de validade.
	 * @return void
	 */
	public static function validarRG(string $sRg): void {
		try {
			if (!preg_match('/^\d{10}$/', $sRg)) {
				throw new Exception("ALERTA! O RG deve conter exatamente 10 dígitos.");
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Valida a data de nascimento.
	 * A data de nascimento deve estar no formato YYYY-MM-DD e a pessoa deve ter pelo menos 18 anos.
	 *
	 * @param string $sDataNascimento Data de nascimento a ser validada.
	 * @throws Exception Se a data de nascimento não estiver no formato correto ou se a pessoa for menor de 18 anos.
	 * @return void
	 */
	public static function validarDataNascimento(string $sDataNascimento): void {
		try {
			$bDate = DateTime::createFromFormat('Y-m-d', $sDataNascimento);
			if (!$bDate || $bDate->format('Y-m-d') !== $sDataNascimento) {
				throw new Exception("ALERTA!: A data de nascimento deve estar no formato YYYY-MM-DD.");
			}

			$today = new DateTime();
			$iIdade = $today->diff($bDate)->y;

			if ($iIdade < 18) {
				throw new Exception("ALERTA!: A pessoa deve ter pelo menos 18 anos.");
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Valida a idade.
	 * A idade deve ser maior ou igual a 18 anos.
	 *
	 * @param int $iIdade Idade a ser validada.
	 * @throws Exception Se a idade não atender ao critério mínimo de 18 anos.
	 * @return void
	 */
	public static function validarIdade(int $iIdade): void {
		try {
			if ($iIdade < 18) {
				throw new Exception("ALERTA! A idade deve ser maior ou igual a 18 anos.");
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Valida o cargo e empresa.
	 * O campo de cargo e empresa deve conter apenas letras, números e espaços.
	 *
	 * @param string $sCargoEmpresa Cargo ou empresa a ser validado.
	 * @throws Exception Se o campo de cargo e empresa não atender aos critérios.
	 * @return void
	 */
	public static function validarCargoEmpresa(string $sCargoEmpresa): void {
		try {
			if (!preg_match('/^[\p{L}0-9\s]+$/u', $sCargoEmpresa)) {
				throw new Exception("ALERTA! O campo deve conter apenas letras e números.");
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Valida o telefone fixo.
	 * O telefone fixo deve conter exatamente 10 dígitos, incluindo o DDD.
	 *
	 * @param string $sTelefoneFixo Número de telefone fixo a ser validado.
	 * @throws Exception Se o telefone fixo não atender aos critérios de validade.
	 * @return void
	 */
	public static function validarTelefoneFixo(string $sTelefoneFixo): void {
		try {
			if (!preg_match('/^\d{10}$/', $sTelefoneFixo)) {
				throw new Exception("ALERTA! O telefone fixo deve conter exatamente 10 dígitos (incluindo DDD).");
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Valida o telefone celular.
	 * O telefone celular deve conter exatamente 11 dígitos, incluindo o DDD.
	 *
	 * @param string $sCelular Número de telefone celular a ser validado.
	 * @throws Exception Se o telefone celular não atender aos critérios de validade.
	 * @return void
	 */
	public static function validarTelefoneCelular(string $sCelular): void {
		try {
			if (!preg_match('/^\d{11}$/', $sCelular)) {
				throw new Exception("ALERTA! O telefone celular deve conter exatamente 11 dígitos (incluindo DDD).");
			}
		} catch (Exception $e) {
			throw $e;
		}
	}
}
?>

