import numpy as np
from core.debug import _DEBUG
class ADERTime(object):
    """
    Time stepper for an ADER scheme
    """
    cfl_max = 0.5
    def __init__(self):
        pass

    def get_dt(self, v, dx, factor=0.9):
        """
        This is replicated with the SSPRK4 scheme. Fix.
        """
        dt = factor * (self.cfl_max * dx) / v
        return np.min(dt)

    def compute(self, f, now, t, dt):
        space_deriv = f(t, now, get_derivs=True)
        term1 = dt * space_deriv[0]
        term2 = -dt * dt / 2.0 * space_deriv[1]
        term3 = dt * dt * dt / 6.0 * space_deriv[2]
        return now + term1 + term2 + term3

################################################################
# TESTS
################################################################
from rupturotops.controller import _test_controller_helper
import rupturotops.wave_forms as wave_forms

def test_ader():
    delta_x = 0.005 * np.ones(200)
    width = np.sum(delta_x)
    def callback(cont):
        cont.timestepper = ADERTime()
        pass
    _test_controller_helper(lambda x: wave_forms.sin_4(x, 5 * np.pi / width),
                            2.0, delta_x, 0.01, setup_callback=callback)

if __name__ == "__main__":
    test_ader()

