
import './shared/justfile'

mod backend
mod frontend

fix:
    just backend::fix

lint: 
    just backend::lint